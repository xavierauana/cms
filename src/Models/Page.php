<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\Cms\Services\ContentService;
use Anacreation\Cms\Services\LanguageService;
use Anacreation\Cms\traits\ContentGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Page extends Model
    implements ContentGroupInterface, CacheManageableInterface, CmsPageInterface
{
    use ContentGroup;

    public $editable = true;

    protected $fillable = [
        'uri',
        'order',
        'template',
        'is_active',
        'has_children',
        'permission_id',
        'is_restricted',
    ];

    # region Relation
    public function children(): Relation {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function parent(): Relation {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function permission(): Relation {
        return $this->belongsTo(Permission::class);
    }
    #endregion

    # region Helpers
    public function hasChildren(): bool {
        return $this->children()->count() > 0;
    }

    public function showPermission(): string {
        return $this->permission ? $this->permission->label : "Not Specified";
    }
    #endregion

    # region Scope
    public function scopeActive(Builder $query): Builder {
        return $query->whereIsActive(true);
    }

    public function scopeTopLevel(Builder $query): Builder {
        return $query->whereParentId(0);
    }

    public function scopeSorted(Builder $query): Builder {
        return $query->latest()->orderBy('order', 'asc');
    }


    #endregion

    // Get Page content
    public function getContent(
        string $identifier, string $default = "", string $langCode = null
    ): ?string {

        $langCode = $langCode ?? app()->getLocale();

        $key = $this->getContentCacheKey($langCode, $identifier);

        $loadContent = function () use ($identifier, $langCode) {
            $content = optional($this->getContentIndex($identifier,
                $langCode))->content;

            return optional($content)->show();
        };

        $value = Cache::has($key) ? Cache::get($key) : $this->loadFromCache($key,
            $loadContent);

        if (isset($value) and !empty($value)) {
            return $value;
        }

        $langService = app()->make(LanguageService::class);
        $language = $langService->getLanguage($langCode);
        $fallbacklang = $language->fallbackLanguage;

        if ($language->fallback_langauge_id == 0 and $langService->getDefaultLanguage()->code == $langCode) {
            return $default;
        }


        return $this->getContent($identifier, $default, $fallbacklang->code);

    }

    // Get Page content
    public function getContentWithTemplate(
        string $identifier, string $template, string $default = "",
        string $langCode = null
    ): ?string {

        $value = $this->getContent($identifier, $default, $langCode);

        return $value ? sprintf($template, $value) : null;

    }

    public
    function getFile(
        string $identifier
    ): ?ContentTypeInterface {

        $contentIndex = $this->getContentIndex($identifier, "file");

        return $contentIndex ? $contentIndex->content : null;
    }

    public
    function injectLayoutModels(): array {
        $vars = [];
        if ($xml = $this->loadDefinitionXML()) {
            foreach ($xml->model as $model) {
                try {
                    $vars[(string)$model->name] = app()->make((string)$model->class);
                } catch (\Exception $e) {
                    Log::error('Layout model injection error: cannot inject model ' . (string)$model->class . " for page " . $this->uri);
                    Log::info($e->getMessage());
                }
            }
        }

        return $vars;
    }

    public
    function loadContents() {
        $contentIdentifiers = [];

        if ($xml = $this->loadDefinitionXML()) {
            $contentIdentifiers = $this->loadPredefinedContentDefinition($xml);
        }

        $contentIdentifiers = $this->loadAdHocContents($contentIdentifiers);

        return $this->loadPageContentWithIdentifiers($contentIdentifiers);

    }

    # region Private functions

    /**
     * @param string      $identifier
     * @param string|null $contentType
     * @param string|null $locale
     * @return mixed
     */
    private
    function getContentIndex(
        string $identifier, string $locale = null
    ): ?ContentIndex {

        return ContentService::getContentIndex($this, $identifier, $locale);
    }

    private
    function fetchLayoutDefinition(): ?string {
        $path = getActiveThemePath() . "/definition";
        $files = File::files($path);
        $layoutDefinition = null;
        foreach ($files as $file) {
            if ($file->getFilename() === $this->template . ".xml") {
                $layoutDefinition = $file->getFilename();
                break;
            }
        }

        return $layoutDefinition;
    }

    private
    function loadDefinitionXML() {

        if ($layoutDefinition = $this->fetchLayoutDefinition()) {

            $filePath = getActiveThemePath() . "/definition/" . $layoutDefinition;

            $xml = simplexml_load_file($filePath);

            return $xml;
        }

        return null;
    }

    private
    function loadPredefinedContentDefinition(
        $xml
    ): array {
        $contents = $this->loadPredefinedIdentifiers($xml);

        return $contents;
    }

    private
    function loadAdHocContents(
        array $predefinedContent = []
    ): array {
        if ($this->editable) {
            $service = app()->make(ContentService::class);
            $adhocContent = [];
            $this->contentIndices()
                 ->select('identifier', 'content_type')->distinct()
                 ->get()->each(function ($item) use (&$adhocContent, $service) {
                    $adhocContent[$item->identifier]['type'] = $service->convertToJsString($item->content_type);
                });;


            return $predefinedContent ? array_merge($adhocContent,
                $predefinedContent) : $adhocContent;
        }

        return $predefinedContent;

    }

    private
    function loadPageContentWithIdentifiers(
        array $contentIdentifiers
    ) {
        $identifiers = array_keys($contentIdentifiers);
        $service = app()->make(ContentService::class);

        $contents = $this->contentIndices()->whereIn('identifier', $identifiers)
                         ->get();

        $contents = $contents->groupBy('identifier')->map(function (
            $collection, $key
        ) use ($service) {
            $data['type'] = null;
            $data['content'] = null;

            $collection->each(function (ContentIndex $ci) use (&$data, $service
            ) {
                if ($data['type'] === null) {
                    $data['type'] = $service->convertToJsString($ci->content_type);
                }
                $data['content'][] = [
                    'lang_id' => $ci->lang_id,
                    'content' => $ci->content->showBackend(),
                ];
            });

            return $data;

        })->toArray();


        return array_merge($contentIdentifiers, $contents);
    }

    private
    function setLayoutEditable(
        $xml
    ) {
        foreach ($xml->attributes() as $attribute => $value) {
            if ($attribute == 'editable' and $value == 'false') {
                $this->editable = false;
                break;
            }
        }
    }

    /**
     * @param $xml
     * @return array
     */
    private
    function loadPredefinedIdentifiers(
        $xml
    ): array {
        $contents = [];
        foreach ($xml->content as $content) {

            if (!array_key_exists((string)$content->identifier, $contents)) {
                $contents[(string)$content->identifier] = [];
                $contents[(string)$content->identifier]['type'] = (string)$content->type;
            }
        }

        $this->setLayoutEditable($xml);

        return $contents;
    }

    #endregion
    public
    function getCacheKey(): string {
        return "page_" . $this->id;
    }

    public
    function getContentCacheKey(
        string $langCode, string $contentIdentifier
    ): string {
        return $this->getCacheKey() . "_" . $langCode . "_" . $contentIdentifier;
    }

    public function getActivePages(): Collection {

        $query = $this->id ? $this->children() : $this->with('children')
                                                      ->topLevel();

        return $query->active()->get();
    }

    public function removeUrlStartSlash(string $uri = null): string {
        $new_uri = $uri ?? $this->uri;
        if (substr($new_uri, 0, 1) == "/") {
            $new_uri = substr($this->uri, 1);

            return $this->removeUrlStartSlash($new_uri);
        }

        return $new_uri;
    }
}
