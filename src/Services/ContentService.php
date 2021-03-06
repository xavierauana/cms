<?php
/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 6:33 PM
 */

namespace Anacreation\Cms\Services;

use Anacreation\Cms\ContentModels\BooleanContent;
use Anacreation\Cms\ContentModels\DateContent;
use Anacreation\Cms\ContentModels\DatetimeContent;
use Anacreation\Cms\ContentModels\FileContent;
use Anacreation\Cms\ContentModels\NumberContent;
use Anacreation\Cms\ContentModels\PlainTextContent;
use Anacreation\Cms\ContentModels\StringContent;
use Anacreation\Cms\ContentModels\TextContent;
use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\Cms\Contracts\ICreateContentObjectFromRequest;
use Anacreation\Cms\Entities\ContentObject;
use Anacreation\Cms\Exceptions\IncorrectContentTypeException;
use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Class ContentService
 * @package Anacreation\Cms\Services
 */
class ContentService
{
    /**
     * ContentTypes array keys are the default content types listed in
     * _definition.dtd
     * Class is the php class responsible for handling backend
     * interaction Component is the vue front end component
     */
    private const ContentTypes = [
        'plain_text' => [
            "class"     => PlainTextContent::class,
            "component" => "PlainTextContent",
        ],
        'text'       => [
            "class"     => TextContent::class,
            "component" => "TextContent",
        ],
        'string'     => [
            "class"     => StringContent::class,
            "component" => "StringContent",
        ],
        'number'     => [
            "class"     => NumberContent::class,
            "component" => "NumberContent",
        ],
        'file'       => [
            "class"     => FileContent::class,
            "component" => "FileContent",
        ],
        'boolean'    => [
            "class"     => BooleanContent::class,
            "component" => "BooleanContent",
        ],
        'datetime'   => [
            "class"     => DatetimeContent::class,
            "component" => "DatetimeContent",
        ],
        'date'       => [
            "class"     => DateContent::class,
            "component" => "DateContent",
        ],
    ];

    private $types = [];

    /**
     * ContentService constructor.
     */
    public function __construct() {
        foreach($this->getContentTypes() as $type => $data) {
            $this->types[] = new ContentTypeStruct($type,
                                                   $data);
        }
    }


    public function getUpdateValidationRules(): array {
        return [
            'identifier'   => "required",
            'lang_id'      => "required|in:".
                              implode(",",
                                      Language::pluck('id')->toArray()),
            'content'      => "nullable",
            'content_type' => "required",
        ];
    }


    /**
     * @param string $jsText
     * @return null|string
     */
    public function convertToContentTypeClass(string $jsText): ?string {
        return $this->getContentType($jsText);
    }

    /**
     * @param ContentTypeInterface|string $content
     * @return null|string
     */
    public function convertToJsString($content): ?string {
        $content = $content instanceof ContentTypeInterface ? get_class($content): $content;

        return $this->getContentType($content,
                                     'class');
    }


    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param \Illuminate\Http\Request                         $request
     * @throws \Anacreation\Cms\Exceptions\IncorrectContentTypeException
     */
    public function updateOrCreateContentIndex(
        ContentGroupInterface $contentOwner, Request $request
    ): void {

        /** @var ICreateContentObjectFromRequest $creator */
        $creator = app(ICreateContentObjectFromRequest::class);

        $this->updateOrCreateContentIndexWithContentObject($contentOwner,
                                                           $creator->create($request));

    }

    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param \Anacreation\Cms\Entities\ContentObject          $contentObject
     * @throws \Anacreation\Cms\Exceptions\IncorrectContentTypeException
     * @throws \Exception
     */
    public function updateOrCreateContentIndexWithContentObject(
        ContentGroupInterface $contentOwner, ContentObject $contentObject
    ): void {


        if(($contentType = $this->convertToContentTypeClass($contentObject->content_type)) === null) {
            throw new IncorrectContentTypeException();
        }

        $contentIndex = $contentOwner->contentIndices()
                                     ->fetchIndex($contentObject->identifier,
                                                  $contentObject->lang_id)
                                     ->first();

        if($this->contentTypeIsSameAsInputContentType($contentIndex,
                                                      $contentType) and ($content = $contentIndex->content)) {
            $contentIndex->content->updateContent($contentObject);
        } else {
            $this->createContent($contentOwner,
                                 $contentObject,
                                 $contentIndex,
                                 $contentType);
        };

        $this->invalidateContentCacheWithContentObject($contentOwner,
                                                       $contentObject);

    }


    /**
     * @param \Illuminate\Http\Request $request
     * @return ContentObject
     */
    public function createContentObject(Request $request): ContentObject {
        return new ContentObject(
            $request->get('identifier'),
            $request->get('lang_id'),
            $request->get('content'),
            $request->get('content_type'),
            $request->file('content'));
    }

    public function loadContentWithIdentifiers(
        ContentGroupInterface $hasContent, array $identifiers
    ) {
        $contents = $hasContent->contentIndices()
                               ->whereIn('identifier',
                                         $identifiers)
                               ->get();

        $contents = $contents->groupBy('identifier')->map(function(
            $collection, $key
        ) {
            $data['type'] = null;
            $data['content'] = null;

            $collection->each(function(ContentIndex $ci) use (&$data
            ) {
                if($data['type'] === null) {
                    $data['type'] = $this->convertToJsString($ci->content_type);
                }
                $data['content'][] = [
                    'lang_id' => $ci->lang_id,
                    'content' => $ci->content->showBackend(),
                ];
            });

            return $data;

        })->toArray();

        return $contents;

    }

    public function loadAdHocContent(
        ContentGroupInterface $hasContent, array $predefinedContent
    ): array {
        $adHocContent = [];
        $hasContent->contentIndices()
                   ->select('identifier',
                            'content_type')->distinct()
                   ->get()->each(function($item) use (&$adHocContent) {
                $adHocContent[$item->identifier]['type'] = $this->convertToJsString($item->content_type);
            });;


        return $predefinedContent ? array_merge($adHocContent,
                                                $predefinedContent): $adHocContent;
    }

    public function deleteContent(
        CmsPageInterface $page, string $identifier, array $queryString
    ): array {
        $query = $page->contentIndices()
                      ->whereIdentifier($identifier);

        if(isset($queryString['remove_content'])) {

            if(isset($queryString['lang_id'])) {

                $contentIndex = $query->whereLangId($queryString['lang_id'])
                                      ->first();
                if($contentIndex) {

                    $contentIndex->content
                        ->deleteContent($queryString);

                    $contentIndex->delete();

                    $content = "deleted";

                    $language = app(LanguageService::class)->getLanguageById($contentIndex->lang_id);

                    $this->invalidateContentCache($page,
                                                  $identifier,
                                                  $language->code);

                } else {
                    $content = "no content found";
                }

            } else {
                $content = "no content index found";
            }

            $response = [
                'status'  => 'completed',
                'content' => $content,
            ];

        } else {

            $query->delete();

            $languages = app(LanguageService::class)->languages;

            foreach($languages as $language) {
                $this->invalidateContentCache($page,
                                              $identifier,
                                              $language->code);
            }


            $response = [
                'status' => 'completed',
            ];
        }

        return $response;
    }

    # region Private Methods

    /**
     * @param string $needle
     * @param string $searchFor
     * @return string
     */
    private function getContentType(string $needle, string $searchFor = 'type'
    ): string {
        if($struct = $this->isAValidContentType($needle,
                                                $searchFor)) {
            return $searchFor === 'type' ? $struct->getClass(): $struct->getType();
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
     * @param ContentObject                                    $contentInput
     * @param                                                  $index
     * @param                                                  $contentType
     */
    private function createContent(
        ContentGroupInterface $contentOwner, ContentObject $contentInput,
        $index, $contentType
    ): void {
        if($index) {
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
     * @param ContentObject                                    $contentObject
     */
    private function invalidateContentCacheWithContentObject(
        ContentGroupInterface $contentOwner, ContentObject $contentObject
    ) {
        $language = (new LanguageService())->getLanguageById($contentObject->lang_id);

        $this->invalidateContentCache($contentOwner,
                                      $contentObject->identifier,
                                      $language->code);
    }

    /**
     * @param string $needle
     * @param string $searchFor
     * @return ContentTypeStruct|null
     */
    private function isAValidContentType(
        string $needle, string $searchFor = 'type'
    ): ?ContentTypeStruct {
        $struct = Arr::first($this->types,
            function(ContentTypeStruct $type) use ($searchFor, $needle) {
                $name = ucwords($searchFor);

                return $type->{"get{$name}"}() === $needle;
            });

        return $struct;
    }

    /**
     * @return array
     */
    public function getTypes(): array {
        return $this->types;
    }

    public function getTypesForJs(): array {
        return array_reduce($this->types,
            function($previous, ContentTypeStruct $struct) {
                $previous[$struct->getType()] = $struct->getComponent();

                return $previous;
            },
                            []);
    }

    # endregion


    /**
     * @param \Anacreation\Cms\Contracts\ContentGroupInterface $contentOwner
     * @param string                                           $identifier
     * @param string                                           $langCode
     */
    public function invalidateContentCache(
        $contentOwner, string $identifier, string $langCode
    ): void {

        if($contentOwner instanceof ContentGroupInterface) {

            $key = $contentOwner->getContentCacheKey($langCode,
                                                     $identifier);

            if(Cache::has($key)) {
                Log::info("Invalidate Content Cache:".$key);
                Cache::forget($key);
            }
        }
    }


}

class ContentTypeStruct
{
    private $type;
    private $class;
    private $component;

    /**
     * ContentTypeStruct constructor.
     */
    public function __construct(string $type, array $data) {

        $this->type = $type;
        $this->class = $data['class'];
        $this->component = $data['component'];
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getComponent() {
        return $this->component;
    }
}
