<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Contracts\CmsPageInterface as Page;
use Anacreation\Cms\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Anacreation\Cms\Models\Page $page
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Page $page) {
        $this->authorize('index', $page);
        $pages = $page->whereParentId(0)->get();

        return view('cms::admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\Page $page
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Page $page) {
        $this->authorize('create', $page);

        $layouts = getLayoutFiles()['layouts'];
        $defaultPermission = [
            0 => 'Not Specified',
        ];
        $permissions = array_merge($defaultPermission,
            Permission::pluck('label', 'id')
                      ->toArray());

        return view('cms::admin.pages.create',
            compact('layouts', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request                    $request
     *
     * @param \Anacreation\Cms\Contracts\CmsPageInterface $page
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Page $page) {
        $this->authorize('store', $page);

        $layouts = getLayoutFiles()['layouts'];

        $validatedInputs = $this->validate($request, [
            'uri'           => 'required|unique:pages',
            'template'      => 'required|in:' . implode(',', $layouts),
            'has_children'  => 'required|boolean',
            'is_active'     => 'required|boolean',
            'is_restricted' => 'required|boolean',
            'permission_id' => 'required|in:0,' . implode(',',
                    Permission::pluck('id')->toArray()),
        ]);

        $page->create($validatedInputs);

        return redirect()->route('pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page) {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page) {
        $this->authorize('edit', $page);

        $layouts = getLayoutFiles()['layouts'];
        $defaultPermission = [
            0 => 'Not Specified',
        ];
        $permissions = array_merge($defaultPermission,
            Permission::pluck('label', 'id')
                      ->toArray());

        return view('cms::admin.pages.edit',
            compact('page', 'layouts', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Page                     $page
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page) {
        $this->authorize('store', $page);

        $layouts = getLayoutFiles()['layouts'];

        $validatedInputs = $this->validate($request, [
            'uri'           => 'required|unique:pages,uri,' . $page->id,
            'template'      => 'required|in:' . implode(',', $layouts),
            'has_children'  => 'required|boolean',
            'is_active'     => 'required|boolean',
            'is_restricted' => 'required|boolean',
            'permission_id' => 'required|in:0,' . implode(',',
                    Permission::pluck('id')->toArray()),
        ]);
        $page->update($validatedInputs);

        return ($parent = $page->parent) ? redirect()->route('contents.index',
            $parent->id) : redirect()->route('pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Page $page
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page) {
        $page->delete();

        return response()->json("Page: {$page->id} deleted!");
    }

    public function postOrder(Request $request, Page $pageRepo) {
        foreach ($request->all() as $data) {
            optional($pageRepo->find($data['id']))->update(['order' => $data['order']]);
        }

        return response()->json('completed');
    }
}
