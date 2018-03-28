<?php
/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 6:33 PM
 */

namespace Anacreation\Cms\Services;

use Anacreation\Cms\ContentModels\BooleanContent;
use Anacreation\Cms\ContentModels\DatetimeContent;
use Anacreation\Cms\ContentModels\FileContent;
use Anacreation\Cms\ContentModels\NumberContent;
use Anacreation\Cms\ContentModels\StringContent;
use Anacreation\Cms\ContentModels\TextContent;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\Cms\Entities\ContentObject;
use Anacreation\Cms\Exceptions\IncorrectContentTypeException;
use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Models\Language;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

/**
 * Class ContentService
 * @package Anacreation\Cms\Services
 */
class ContentService
{
    /**
     *
     */
    protected const ContentTypes = [
        'text'     => TextContent::class,
        'string'   => StringContent::class,
        'number'   => NumberContent::class,
        'file'     => FileContent::class,
        'boolean'  => BooleanContent::class,
        'datetime' => DatetimeContent::class,
    ];

    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param string                                           $identifier
     * @param string|null                                      $locale
     * @return \Anacreation\Cms\Models\ContentIndex|null
     */
    public static function getContentIndex(
        ContentGroupInterface $contentOwner,
        string $identifier, string $locale = null
    ): ?ContentIndex {

        /** @var \Anacreation\CMS\Services\LanguageService $langService */
        $langService = app()->make(LanguageService::class);

        $language = $langService->getLanguage($locale ?? app()->getLocale());

        return static::fetchContentIndexWith($contentOwner, $language,
            $identifier);
    }


    /**
     * @param string $jsText
     * @return null|string
     */
    public function convertToContentTypeClass(string $jsText): ?string {
        return $this->getContentType($this->getContentTypes(), $jsText);
    }

    /**
     * @param ContentTypeInterface|string $content
     * @return null|string
     */
    public function convertToJsString($content): ?string {
        $content = $content instanceof ContentTypeInterface ? get_class($content) : $content;

        return $this->getContentType(array_flip($this->getContentTypes()),
            $content);
    }


    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param \Anacreation\CMS\Entities\ContentObject          $contentInput
     * @throws \Anacreation\Cms\Exceptions\IncorrectContentTypeException
     */
    public function updateOrCreateContentIndex(
        ContentGroupInterface $contentOwner, ContentObject $contentInput
    ): void {

        if (($contentType = $this->convertToContentTypeClass($contentInput->content_type)) === null) {
            throw new IncorrectContentTypeException();
        }

        $this->invalidateContentCache($contentOwner, $contentInput);


        $index = $contentOwner->contentIndices()
                              ->fetchIndex($contentInput->identifier,
                                  $contentInput->lang_id)
                              ->first();

        if ($this->contentTypeIsSameAsInputContentType($index, $contentType)) {
            $index->content->updateContent($contentInput);
        } else {
            $this->createContent($contentOwner, $contentInput, $index,
                $contentType);
        };

    }

    /**
     * @param array                              $data
     * @param \Illuminate\Http\UploadedFile|null $file
     * @return \Anacreation\Cms\Entities\ContentObject
     */
    public function createContentObject(array $data, UploadedFile $file = null
    ): ContentObject {
        return new ContentObject($data['identifier'], $data['lang_id'],
            $data['content'], $data['content_type'], $file);
    }

    # region Private Methods

    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param \Anacreation\Cms\Models\Language                 $language
     * @param string                                           $identifier
     * @return \Anacreation\Cms\Models\ContentIndex|null
     */
    private static function fetchContentIndexWith(
        ContentGroupInterface $contentOwner, Language $language,
        string $identifier
    ): ?ContentIndex {
        return $contentOwner->contentIndices()
                            ->fetchIndex($identifier, $language->id)
                            ->first() ??
               $contentOwner->contentIndices()->fetchIndex($identifier,
                   $language->fallbackLanguage->id)
                            ->first();
    }

    /**
     * @param array  $types
     * @param string $needle
     * @return string
     */
    private function getContentType(array $types, string $needle): string {
        if ($this->isAValidContentType($types, $needle)) {
            return $types[$needle];
        };
        throw new \InvalidArgumentException("jsText Content type is invalid! Or it haven't register yet!");

    }

    /**
     * @return array
     */
    private function getContentTypes(): array {
        return ContentService::ContentTypes + config("cms.content_type");
    }

    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param \Anacreation\CMS\Entities\ContentObject          $contentInput
     * @param                                                  $index
     * @param                                                  $contentType
     */
    private function createContent(
        ContentGroupInterface $contentOwner, ContentObject $contentInput,
        $index, $contentType
    ): void {
        if ($index) {
            $index->delete();
        }
        /** @var ContentTypeInterface $content */
        $content = app()->make($contentType);

        $content->saveContent($contentInput);
        $contentOwner->contentIndices()->create([
            'content_type' => get_class($content),
            'content_id'   => $content->id,
            'lang_id'      => $contentInput->lang_id,
            'identifier'   => $contentInput->identifier,
        ]);
    }

    /**
     * @param $index
     * @param $contentType
     * @return bool
     */
    private function contentTypeIsSameAsInputContentType($index, $contentType
    ): bool {
        return $index and $index->content_type === $contentType;
    }

    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param \Anacreation\Cms\Entities\ContentObject          $contentObject
     */
    private function invalidateContentCache(
        ContentGroupInterface $contentOwner, ContentObject $contentObject
    ) {


        $language = (new LanguageService())->getLanguageById($contentObject->lang_id);
        $key = $contentOwner->getContentCacheKey($language->code,
            $contentObject->identifier);

        \Debugbar::info("Invalidate Cache:" . $key);

        if (Cache::has($key)) {
            Cache::forget($key);
        }
    }

    /**
     * @param array  $types
     * @param string $needle
     * @return bool
     */
    private function isAValidContentType(array $types, string $needle): bool {
        return in_array($needle, array_keys($types));
    }

    # endregion


}