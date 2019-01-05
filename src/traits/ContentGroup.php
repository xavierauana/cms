<?php
/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 9:32 AM
 */

namespace Anacreation\Cms\traits;


use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Services\ContentService;
use Anacreation\Cms\Services\LanguageService;
use Anacreation\Cms\Services\TemplateParser;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait ContentGroup
{

    public $editable = true;

    public function contentIndices(): Relation {
        return $this->morphMany(ContentIndex::class, 'group');
    }

    /**
     * @param          $key
     * @param callable $callable
     * @return mixed
     */
    protected function loadFromCache($key, callable $callable) {
        $duration = config("cms.content_cache_duration");

        if (Cache::has($key)) {

            return Cache::get($key);

        } else {

            $value = $callable();

            Cache::put($key, $value ?? "", $duration);

            return $value;
        }
    }

    public function loadContents(string $path, string $template) {

        $contentIdentifiers = $this->fetchContentIdentifiers($path, $template);

        return $this->loadContentWithIdentifiers($contentIdentifiers);

    }

    private function loadContentWithIdentifiers(array $contentIdentifiers) {

        $contents = app()->make(ContentService::class)
                         ->loadContentWithIdentifiers($this,
                             array_keys($contentIdentifiers));

        foreach ($contentIdentifiers as $identifier => $definition) {
            if (isset($contents[$identifier]) and isset($contents[$identifier]['content'])) {
                $contentIdentifiers[$identifier]['content'] = $contents[$identifier]['content'];
            }
        }

        return $contentIdentifiers;
    }

    private function loadAdHocContents(array $predefinedContent = []): array {
        if ($this->editable) {

            $service = app()->make(ContentService::class);

            return $service->loadAdHocContent($this,
                $predefinedContent);
        }

        return $predefinedContent;
    }

    public function getContents(string $langCode = null) {

        $identifiers = $this->contentIndices()
                            ->pluck('identifier')
                            ->unique()->values()->all();
        $contents = [];
        foreach ($identifiers as $identifier) {

            $content = $this->getContent($identifier, "", $langCode);

            $contents[$identifier] = $content;
        }


        return $contents;
    }

    public function getContent(
        string $identifier, string $default = "", string $langCode = null,
        array $params = []
    ) {
        $langCode = $langCode ?? app()->getLocale();

        $key = $this->getContentCacheKey($langCode, $identifier);

        $loadContent = function () use ($identifier, $langCode, $params) {
            $content = optional($this->getContentIndex($identifier,
                $langCode))->content;

            return optional($content)->show($params);
        };

        $value = Cache::has($key) ? Cache::get($key) : $this->loadFromCache($key,
            $loadContent);

        if (isset($value) and !empty($value)) {
            return $value;
        }

        if ($langCode === app(LanguageService::class)->getDefaultLanguage()->code) {
            return $default;
        }

        $fallbackLang = app(LanguageService::class)->getFallbackLanguage($langCode);

        return $this->getContent($identifier, $default, $fallbackLang->code,
            $params);

    }

    public function getContentIndex(
        string $identifier, string $locale = null
    ): ?ContentIndex {

        $language = app(LanguageService::class)->getLanguage($locale ?? app()->getLocale());

        $index = $this->contentIndices()
                      ->with('content')
                      ->fetchIndex($identifier, $language->id)
                      ->first();

        return $index;
    }

    public function getContentWithTemplate(
        string $identifier, string $template, string $default = "",
        string $langCode = null
    ): ?string {

        $value = $this->getContent($identifier, $default, $langCode);

        return $value ? sprintf($template, $value) : null;

    }

    public function getFile(string $identifier): ?ContentTypeInterface {

        $index = $this->getContentIndex($identifier);

        return $index ? $index->content : null;
    }

    public function injectLayoutModels(string $path = null, string $template
    ): array {
        $vars = [];
        $templateParser = new TemplateParser();
        $path = $path ?: getActiveThemePath();
        $xml = $templateParser->loadTemplateDefinition($path, $template);
        if ($xml) {
            foreach ($xml->model as $model) {
                try {
                    $vars[(string)$model->name] = app()->make((string)$model->class);
                } catch
                (\Exception $e) {
                    Log::error('Layout model injection error: cannot inject model ' . (string)$model->class . " for " . $this);
                    Log::info($e->getMessage());
                }
            }
        }

        return $vars;
    }

    /**
     * @param string $path
     * @param string $template
     * @return array
     */
    private function fetchContentIdentifiers(string $path, string $template
    ): array {

        $contentIdentifiers = (new TemplateParser)->loadPredefinedIdentifiers($path,
            $template, $this->editable);

        $contentIdentifiers = $this->loadAdHocContents($contentIdentifiers);

        return $contentIdentifiers;
    }

}