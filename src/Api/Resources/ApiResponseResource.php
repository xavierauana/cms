<?php

namespace Anacreation\Cms\Api\Resources;

use Anacreation\Cms\Services\ApiEncoder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ApiResponseResource extends Resource
{
    private $level;

    /**
     * ApiResponseResource constructor.
     */
    public function __construct($resource, int $level = null) {

        parent::__construct($resource);

        $this->level = $level;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {

        $endcoder = new ApiEncoder();

        $locale = $request->get('locale', app()->getLocale());
        $hasChildren = $this->hasChildren();

        $data = [
            'id'          => $endcoder->encode(['id' => $this->id]),
            'locale'      => $locale,
            'hasChildren' => $hasChildren,
            'url'         => $this->getAbsoluteUrl(),
            'contents'    => $this->getContents($locale),
        ];

        if ($this->includeChildren($request) and $hasChildren) {
            $perPage = $request->get('perPage', 15);
            $paginator = $this->children()->active()
                              ->sorted()->paginate($perPage);
            list($data['meta'], $data['links']) = $this->parsePaginator($paginator);
            $data['children'] = ApiChildrenResponseResource::collection($paginator);
        }


        return $data;
    }

    private function includeChildren(Request $request) {
        if ($this->level !== 1) {
            return false;
        }

        $includes = array_map('strtolower',
            explode(',', $request->get('includes', "")));

        return in_array('children', $includes);
    }

    private function parsePaginator($paginator): array {

        $path = $this->constructPath();
        $meta = $links = [];

        $meta['count'] = $paginator->count();
        $meta['has_more'] = $paginator->hasMorePages();
        $meta['per_page'] = (int)$paginator->perPage();
        $meta['total'] = $paginator->total();
        $meta['first_item'] = $paginator->firstItem();
        $meta['last_item'] = $paginator->lastItem();
        $meta['current_page'] = $paginator->currentPage();

        $links['last_page'] = $path . "&page=" . $paginator->lastPage();
        $links['next_page'] = ($paginator->lastPage() > $paginator->currentPage()) ? $path . "&page=" . ($paginator->currentPage() + 1) : null;
        $links['first_page'] = $path . "&page=1";
        $links['previous_page'] = $paginator->onFirstPage() ? null : $path . "&page=" . ($paginator->currentPage() - 1);

        return [$meta, $links];
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    private function constructPath() {
        $request = request();

        $path = $request->path() . "?includes=" . $request->get('includes');

        if ($perPage = $request->get('perPage', null)) {
            $path .= "&perPage={$perPage}";
        }

        return url($path);
    }
}
