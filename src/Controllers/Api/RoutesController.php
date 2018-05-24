<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers\Api;


use Anacreation\Cms\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null              $page
     */
    public function resolve(Request $request, string $uri = null) {

        $queries = $request->query();

        if ($page = $this->fetchPage($uri, $queries)) {

            $returnContents = $this->fetchContent($page, $queries);

            return response()->json($returnContents);
        }

        return response("Page not found", 404);

    }

    /**
     * @param \Anacreation\Cms\Models\Page $page
     * @param array                        $queries
     * @return array
     */
    private function fetchContent(Page $page, array $queries = []): array {

        $paginate = in_array("paginate",
            array_keys($queries)) ? $queries['paginate'] : 0;

        if (in_array("withChildren",
                array_keys($queries)) and $queries['withChildren'] === "true") {

            $is_recursive = (in_array("recursive",
                    array_keys($queries)) and $queries['recursive'] === "true");

            $returnContents = $this->fetchChildrenContent($page, $paginate,
                true, $is_recursive
            );
        } else {
            $returnContents = $this->fetchChildrenContent($page, 0,
                false, false);
        }


        return $returnContents;
    }

    /**
     * @param $pageContents
     * @param $returnContents
     * @return mixed
     */
    private function parseContent(array $pageContents) {
        $returnContents = [];
        foreach ($pageContents as $identifier => $val) {
            $keys = array_keys($val);
            if (in_array('content', $keys)) {
                $returnContents[$identifier] = $val;
            }
        }

        return $returnContents;
    }

    /**
     * @param \Anacreation\Cms\Models\Page $page
     * @param int                          $paginate
     * @param bool                         $withChildren
     * @param bool                         $recursive
     * @return mixed
     */
    private function fetchChildrenContent(
        Page $page, int $paginate = 0, bool $withChildren = false,
        bool $recursive = false, array $parents = null
    ) {

        $returnContents['content'] = $this->parseContent($page->loadContents());

        if ($page->parent) {
            $parents[] = $page->parent;
        }

        if ($withChildren === false) {
            return $returnContents;
        }

        $total = null;

        $query = $page->children()->active()->sorted();

        if ($paginate > 0 and $query->count()) {
            $children = $query->paginate($paginate);
            $total = $query->count();

            if ($total) {
                $returnContents['paginator'] = [
                    'last_page'     => $children->lastPage(),
                    'current_page'  => request()->query('current_page') ?? 1,
                    'previous_page' => $children->previousPageUrl(),
                    'count'         => $paginate,
                    'total'         => $total,
                ];
            }

        } else {
            $children = $query->get();
        }

        if (!$children->count()) {
            return $returnContents;
        }
        foreach ($children as $page) {
            $childrenContents[$page->uri] = $this->fetchChildrenContent($page,
                $paginate, $recursive, $recursive, $parents);
        }

        $returnContents['children'] = $childrenContents;

        return $returnContents;
    }

    /**
     * @param string $uri
     * @return mixed
     */
    private function fetchPage(string $uri, array $queries): ?Page {

        // TODO:: Need to check authentication and authorization

        if ($uri and $page = Page::with('children')->topLevel()
                                 ->whereUri($uri === 'home' ? "/" : $uri)
                                 ->first()) {

            if (in_array('children', array_keys($queries))) {
                foreach (explode(',', $queries['children']) as $childUri) {
                    $page = $page->children()->with('children')->active()
                                 ->whereUri($childUri)->first();

                    if ($page === null) {
                        return response("No Page Found!", 404);
                    }
                }
            }


            return $page;

        }

        return null;

    }
}