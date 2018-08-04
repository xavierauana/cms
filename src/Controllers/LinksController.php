<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Entities\ContentObject;
use Anacreation\Cms\Events\LinkDeleted;
use Anacreation\Cms\Events\LinkSaved;
use Anacreation\Cms\Events\MenuSaved;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Link;
use Anacreation\Cms\Models\Menu;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Requests\Links\UpdateRequest;
use Anacreation\Cms\Services\ContentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class LinksController
 * @package Anacreation\Cms\Controllers
 */
class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Link $model
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Link $model) {
        $this->authorize('index', $model);

        $links = Link::all();

        return view('cms::admin.links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\Menu $menu
     * @param \Anacreation\Cms\Models\Page $page
     * @param \Anacreation\Cms\Models\Link $model
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Menu $menu, Page $page, Link $model) {
        $this->authorize('create', $model);

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
     * @param \Anacreation\Cms\Models\Page $page
     * @param \Anacreation\Cms\Models\Link $model
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(
        Menu $menu, Request $request, Page $page, Link $model
    ) {
        $this->authorize('store', $model);

        $this->sanitizeInputs($request);

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

        $newLink = $this->createLink($menu, $validatedData);

        event(new MenuSaved($newLink->menu));
        event(new LinkSaved($newLink));

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
     * @param \Anacreation\Cms\Models\Menu $menu
     * @param \Anacreation\Cms\Models\Link $link
     * @param \Anacreation\Cms\Models\Page $page
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
     * @param \Anacreation\Cms\Requests\Links\UpdateRequest $request
     * @param \Anacreation\Cms\Models\Menu                  $menu
     * @param Link                                          $link
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Menu $menu, Link $link) {

        $this->updateLink($link, $request->validated());

        event(new MenuSaved($link->menu));
        event(new LinkSaved($link));

        return redirect('/admin/menus');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Anacreation\Cms\Models\Menu $menu
     * @param \Anacreation\Cms\Models\Link $link
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Menu $menu, Link $link) {

        $this->authorize('update', $link);

        $menu->links()->find($link->id)->delete();

        event(new MenuSaved($menu));
        event(new LinkDeleted($link));

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
     * @param                                          $validatedData
     * @return \Anacreation\Cms\Models\Link
     */
    private function createLink(
        Menu $menu, $validatedData
    ): Link {
        $service = new ContentService();

        /** @var Link $newLink */
        $newLink = $menu->links()->create($validatedData);

        foreach ($validatedData['name'] as $data) {
            $contentObject = new ContentObject('link', $data['lang_id'],
                $data['content'], 'string');
            $service->updateOrCreateContentIndexWithContentObject($newLink,
                $contentObject);
        }

        return $newLink;
    }

    /**
     * @param $link
     * @param $validatedData
     */
    private function updateLink($link, $validatedData) {

        $service = new ContentService();

        if (!isset($validatedData['page_id'])) {
            $validatedData['page_id'] = 0;
        }

        $link->update($validatedData);

        foreach ($validatedData['name'] as $data) {
            $contentObject = new ContentObject('link', $data['lang_id'],
                $data['content'], 'string');
            $service->updateOrCreateContentIndexWithContentObject($link,
                $contentObject);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Request
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
