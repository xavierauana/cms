<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Entities\ContentObject;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Link;
use Anacreation\Cms\Models\Menu;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\ContentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->authorize('index', new Link());

        $links = Link::all();

        return view('cms::admin.links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Menu $menu, Page $page) {
        list($pages, $links) = $this->loadResources($menu, $page);
        $languages = Language::all();

        return view('cms::admin.links.create',
            compact('menu', 'pages', 'links', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Anacreation\Cms\Models\Menu $menu
     * @param  \Illuminate\Http\Request    $request
     * @return void
     */
    public function store(
        Menu $menu, Request $request, Page $page
    ) {
        $request = $this->sanitizeInputs($request);

        $standard = $page->pluck('id')->toArray();

        $ids = implode(",", $standard);

        $validatedData = $this->validate($request, [
            'name.*.lang_id' => 'required:in:' . implode(",",
                    Language::pluck('id')->toArray()),
            'name.*.content' => 'required',
            'is_active'      => 'required|boolean',
            'parent_id'      => 'required|in:0,' . implode(",",
                    $menu->links()->pluck('id')->toArray()),
            'page_id'        => 'required_without:external_uri|in:' . $ids,
            'external_uri'   => 'required_without:page_id',
        ]);

        $this->createLink($menu, $validatedData);

        return redirect("/admin/menus");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Link $link
     * @return \Illuminate\Http\Response
     */
    public function show(Link $link) {
        dd('hrere');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Link $link
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu, Link $link, Page $page) {
        list($pages, $links) = $this->loadResources($menu, $page);
        $languages = Language::all();

        return view('cms::admin.links.edit',
            compact("menu", "links", "link", "pages", "languages"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Link                $link
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu, Link $link, Page $page
    ) {

        $request = $this->sanitizeInputs($request);

        $standard = $page->pluck('id')->toArray();

        $ids = implode(",", $standard);


        $validatedData = $this->validate($request, [
            'name.*.lang_id' => 'required:in:' . implode(",",
                    Language::pluck('id')->toArray()),
            'name.*.content' => 'required',
            'is_active'      => 'required|boolean',
            'parent_id'      => 'required|in:0,' . implode(",",
                    $menu->links()->pluck('id')->toArray()),
            'page_id'        => 'required_without:external_uri|in:' . $ids,
            'external_uri'   => 'required_without:page_id'
        ]);


        $this->updateLink($link, $validatedData);

        return redirect('/admin/menus');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Link $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu, Link $link) {
        $menu->links()->find($link->id)->delete();

        return redirect('admin/menus');
    }

    /**
     * @param \Anacreation\Cms\Models\Menu $menu
     * @param \Anacreation\Cms\Models\Page $page
     * @return array
     */
    private function loadResources(Menu $menu, Page $page): array {
        $pages[] = "Not Link to page";
        $pages = $pages + $page->pluck('uri', 'id')->toArray();
        $links['0'] = "Top Level";
        $nameAndId = $menu->links->reduce(function ($previous, Link $link) {
            $previous[$link->id] = $link->name;

            return $previous;
        }, []);
        $links = $links + $nameAndId;

        return array($pages, $links);
    }

    /**
     * @param \Anacreation\Cms\Models\Menu             $menu
     * @param \Anacreation\Cms\Services\ContentService $service
     * @param                                          $validatedData
     * @throws \Anacreation\Cms\Exceptions\IncorrectContentTypeException
     */
    private function createLink(
        Menu $menu, $validatedData
    ): void {
        $service = new ContentService();

        /** @var \Anacreation\Cms\Models\Link $newLink */
        $newLink = $menu->links()->create($validatedData);

        foreach ($validatedData['name'] as $data) {
            $contentObject = new ContentObject('link', $data['lang_id'],
                $data['content'], 'string');
            $service->updateOrCreateContentIndex($newLink, $contentObject);
        }
    }

    private function updateLink($link, $validatedData) {

        $service = new ContentService();

        $link->update($validatedData);

        foreach ($validatedData['name'] as $data) {
            $contentObject = new ContentObject('link', $data['lang_id'],
                $data['content'], 'string');
            $service->updateOrCreateContentIndex($link, $contentObject);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    private function sanitizeInputs(Request $request): Request {
        $inputs = $request->all();
        if (isset($inputs['external_uri']) and !$inputs['external_uri']) {
            unset($inputs['external_uri']);
        }
        if (isset($inputs['page_id']) and !$inputs['page_id']) {
            unset($inputs['page_id']);
        }

        $request->replace($inputs);

        return $request;
    }
}
