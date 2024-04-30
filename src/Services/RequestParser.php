<?php
/**
 * Author: Xavier Au
 * Date: 29/3/2018
 * Time: 9:55 AM
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Contracts\RequestParserInterface;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;
use Illuminate\Http\Request;

class RequestParser implements RequestParserInterface
{
    private $segments = [];

    /**
     * RequestParser constructor.
     */
    public function __construct()
    {
        $this->segments = request()->segments();
    }

    public function parse(Request $request, array $vars = null): ?array
    {

        $decodePath = $this->getSanitizedPath($request);

        if ($page = ((Page::ActivePages())[$decodePath] ?? null)) {
            return $this->createData($page);
        }

        return null;

    }

    private function createData(CmsPageInterface $page)
    {
        $data = $page->injectLayoutModels(null, $page->getTemplate());
        $data['page'] = $page;
        $data['language'] = new Language;
        $data['common'] = new CommonContentService;

        return $data;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function getSanitizedPath(Request $request): string
    {
        $decodePath = urldecode($request->path());

        $languageService = new LanguageService();

        $activeLanguageCodes = $languageService->activeLanguages->pluck('code')->toArray();

        if (in_array($this->segments[0], $activeLanguageCodes)) {
            $decodePath = str_replace($this->segments[0] . '/', '', $decodePath);
        }

        return $decodePath;
    }
}
