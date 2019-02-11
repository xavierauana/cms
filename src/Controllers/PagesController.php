<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Contracts\CmsPageInterface as Page;
use Anacreation\Cms\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function index(Page $page, Request $request) {

        $this->authorize('index', $page);
        $pages = $page->whereParentId(0)
                      ->searchable($request->query('keyword'))
                      ->sortable()
                      ->latest()
                      ->paginate();

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
        $this->authorize('create', $page);

        $layouts = getLayoutFiles()['layouts'];

        $validatedInputs = $this->validate($request, [
            'uri'           => [
                'required',
                'not_in:api,modules',
                Rule::unique('pages')->where(function ($query) {
                    return $query->where('parent_id', 0);
                })
            ],
            'template'      => 'required|in:' . implode(',', $layouts),
            'has_children'  => 'required|boolean',
            'is_active'     => 'required|boolean',
            'is_restricted' => 'required|boolean',
            'order'         => 'nullable|numeric|min:0',
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
        $this->authorize('show', $page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Page $page) {
        $this->authorize('edit', $page);

        $layouts = getLayoutFiles()['layouts'];

        $validatedInputs = $this->validate($request, [
            'uri'           => [
                'required',
                'not_in:api,modules',
                Rule::unique('pages')
                    ->ignore($page->id)
                    ->where(function ($query) use ($page) {
                        return $query->where('parent_id',
                            $page->parent_id);
                    })
            ],
            'template'      => 'required|in:' . implode(',', $layouts),
            'has_children'  => 'required|boolean',
            'is_active'     => 'required|boolean',
            'is_restricted' => 'required|boolean',
            'order'         => 'nullable|numeric|min:0',
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Page $page) {
        $this->authorize('delete', $page);

        $page->delete();

        return response()->json("Page: {$page->id} deleted!");
    }

    public function postOrder(Request $request, Page $pageRepo) {
        $this->authorize('edit', $pageRepo);

        foreach ($request->all() as $data) {
            optional($pageRepo->find($data['id']))->update(['order' => $data['order']]);
        }

        return response()->json('completed');
    }
}
