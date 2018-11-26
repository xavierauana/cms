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

        $contentIdentifiers = [];

        $templateParser = new TemplateParser();

        $xml = $templateParser->loadTemplateDefinition($path,
            $template);

        if ($xml) {
            $contentIdentifiers = $templateParser->loadPredefinedIdentifiers($xml,
                $this->editable);
        }

        $contentIdentifiers = $this->loadAdHocContents($contentIdentifiers);

        return $this->loadContentWithIdentifiers($contentIdentifiers);

    }

    private function loadContentWithIdentifiers(array $contentIdentifiers) {
        $identifiers = array_keys($contentIdentifiers);
        $service = app()->make(ContentService::class);

        $contents = $service->loadContentWithIdentifiers($this, $identifiers);

        return array_merge($contentIdentifiers, $contents);
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

        $langService = app(LanguageService::class);
        $language = $langService->getLanguage($langCode);
        $fallbackLang = $language->fallbackLanguage;

        if ($language->fallback_langauge_id == 0 and $langService->getDefaultLanguage()->code == $langCode) {
            return $default;
        }


        return $this->getContent($identifier, $default, $fallbackLang->code,
            $params);

    }

    private function getContentIndex(
        string $identifier, string $locale = null
    ): ?ContentIndex {

        return ContentService::getContentIndex($this, $identifier, $locale);
    }

    public function getContentWithTemplate(
        string $identifier, string $template, string $default = "",
        string $langCode = null
    ): ?string {

        $value = $this->getContent($identifier, $default, $langCode);

        return $value ? sprintf($template, $value) : null;

    }

    public function getFile(string $identifier): ?ContentTypeInterface {

        $contentIndex = $this->getContentIndex($identifier);

        return $contentIndex ? $contentIndex->content : null;
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

}