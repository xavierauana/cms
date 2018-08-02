<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Models\Permission;
use Anacreation\Cms\Services\ContentService;
use Anacreation\Cms\Services\LanguageService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Anacreation\Cms\Models\Page              $page
     * @param \Anacreation\Cms\Services\LanguageService $langService
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Page $page, LanguageService $langService) {
        $this->authorize('edit', $page);

        $contents = $page->loadContents(getActiveThemePath(), $page->template);
        $languages = $langService->activeLanguages;

        return view("cms::admin.contents.index",
            compact('page', 'contents', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\Page $page
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Page $page) {
        $this->authorize('edit', $page);

        $layouts = getLayoutFiles()['layouts'];

        $defaultPermission = [
            0 => "Not Specified"
        ];
        $permissions = array_merge($defaultPermission,
            Permission::pluck('label', 'id')
                      ->toArray());

        return view('cms::admin.contents.create',
            compact('page', 'layouts', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param \Anacreation\Cms\Models\Page $page
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Page $page) {

        $layouts = getLayoutFiles()['layouts'];

        $validatedInputs = $this->validate($request, [
            'uri'           => 'required|unique:pages',
            'template'      => 'required|in:' . implode(',', $layouts),
            'has_children'  => 'required|boolean',
            'is_active'     => 'required|boolean',
            'is_restricted' => 'required|boolean',
            'permission_id' => 'in:0,' . implode(Permission::pluck('id')
                                                           ->toArray()),
        ]);

        $page->children()->create($validatedInputs);

        return redirect()->route('contents.index', $page->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $contentIndex
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request                $request
     * @param \Anacreation\Cms\Models\Page             $page
     * @param \Anacreation\Cms\Models\ContentIndex     $contentIndex
     * @param \Anacreation\Cms\Services\ContentService $service
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Anacreation\Cms\Exceptions\IncorrectContentTypeException
     */
    public
    function update(
        Request $request, Page $page, ContentIndex $contentIndex,
        ContentService $service
    ) {
        $this->authorize('update', $contentIndex);

        $validatedData = $this->validate($request,
            $service->getUpdateValidationRules());

        $service->updateOrCreateContentIndex($page,
            $service->createContentObject($validatedData,
                $request->file('content')));

        return response()->json("done");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Anacreation\Cms\Models\Page             $page
     * @param string                                   $contentIdentifier
     * @param \Illuminate\Http\Request                 $request
     * @param \Anacreation\Cms\Services\ContentService $service
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public
    function destroy(
        Page $page, string $contentIdentifier, Request $request,
        ContentService $service
    ) {

        $this->authorize('delete', new ContentIndex());

        $queryString = $request->query();

        $query = $page->contentIndices()
                      ->whereIdentifier($contentIdentifier);

        $responseData = $service->deleteContent($queryString, $query);

        return response()->json(array_merge($responseData,
            ['identifier' => $contentIdentifier]));

    }

    public function destroyChild(Page $page, int $childId) {
        $this->authorize('delete', $page);

        if ($child = $page->children()->find($childId)) {
            $child->delete();
        }

        return response()->json([
            'status'  => 'completed',
            'childId' => $childId
        ]);
    }


}
