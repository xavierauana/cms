<?php
/**
 * Author: Xavier Au
 * Date: 29/3/2018
 * Time: 9:55 AM
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\Contracts\RequestParserInterface;
use Anacreation\Cms\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApiRequestParser implements RequestParserInterface
{

    private $segments = [];

    public function __construct() {
        $this->segments = request()->segments();
        $this->updateRequestSegments();
    }

    public function parse(Request $request, array $vars = null): ?array {

        $pageRepo = $vars['page'] ?? app(Page::class);

        $activePages = $pageRepo->getActivePages();

        if ($this->requestIsHome($request)) {

            if ($page = $activePages->first(function (Page $page) {
                return $page->uri == "/";
            })) {
                return $this->createData($page);
            }
            throw new \Exception("No Home Page");
        }

        if ($page = $this->getPage($activePages)) {

            $this->updateRequestSegments();

            return $this->createResponseData($request, $page);
        };

        return null;
    }

    private function requestIsHome(): bool {
        return !isset($this->segments[0]) or $this->segments[0] == "" or $this->segments[0] == "/";
    }

    private function createData($page) {
        $data['page'] = $page;
        $data['language'] = new \Anacreation\Cms\Models\Language;

        return $data;
    }

    private function getPage(Collection $activePages): ?Page {
        return $activePages->first(function (Page $page) {
            $clearUri = $page->removeUrlStartSlash();
            $uri = $this->segments[0];

            return $clearUri == $uri;
        });
    }

    private function updateRequestSegments(): void {
        unset($this->segments[0]);
        $this->segments = array_values($this->segments);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $page
     * @return array|mixed|null
     * @throws \Exception
     */
    private function createResponseData(Request $request, $page) {

        if (count($this->segments)) {
            return $this->parse($request, ['page' => $page]);
        }

        return $this->createData($page);
    }

}