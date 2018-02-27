<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Models\ContentIndex;
use Anacreation\Cms\Models\Language;
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
     * @param \Anacreation\Cms\Models\Page             $page
     * @param \Anacreation\Cms\Models\ContentIndex     $content
     * @param \Illuminate\Http\Request                 $request
     * @param \Anacreation\Cms\Services\ContentService $service
     * @return \Illuminate\Http\Response
     */
    public function index(Page $page, LanguageService $langService) {
        $this->authorize('edit', $page);

        $contents = $page->loadContents();
        $languages = $langService->activeLanguages;

        return view("cms::admin.contents.index",
            compact('page', 'contents', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\Page $page
     * @return \Illuminate\Http\Response
     */
    public
    function create(
        Page $page
    ) {
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
    public
    function store(
        Request $request, Page $page
    ) {

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
    //    public
    //    function edit(
    //        Request $request, Page $page, Page $child, ContentService $service
    //    ) {
    //
    //        if ($request->ajax()) {
    //            $contents = $child->contentIndices()
    //                              ->with('content')
    //                              ->whereIdentifier($request->get('identifier'))
    //                              ->get();
    //
    //            return response()->json($contents);
    //        }
    //
    //        $contents = $child->contentIndices()->distinct()
    //                          ->get(['identifier', 'content_type', 'content_id']);
    //
    //        $contents = $contents->map(function (ContentIndex $content) use (
    //            $service
    //        ) {
    //
    //            return [
    //                'type'       => $service->convertToJsString($content->content),
    //                'identifier' => $content->identifier
    //            ];
    //        });
    //
    //
    //        return redirect('pages/' . $child->id . '/contents');
    //
    //        return view('cms::admin.contesnt.edit',
    //            compact('page', 'child', 'contents'));
    //    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request                $request
     * @param \Anacreation\Cms\Models\Page             $page
     * @param \Anacreation\Cms\Models\ContentIndex     $contentIndex
     * @param \Anacreation\Cms\Services\ContentService $service
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public
    function update(
        Request $request, Page $page, ContentIndex $contentIndex,
        ContentService $service
    ) {
        $this->authorize('update', $contentIndex);

        $validatedData = $this->validate($request, [
            'identifier'   => "required",
            'lang_id'      => "required|in:" .
                              implode(",",
                                  Language::pluck('id')->toArray()),
            'content'      => "nullable",
            'content_type' => "required",
        ]);

        $service->updateOrCreateContentIndex($page,
            $service->createContentObject($validatedData),
            $request->file('content'));

        return response()->json("done");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Anacreation\Cms\Models\Page $page
     * @param string                       $contentIdentifier
     * @param \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public
    function destroy(
        Page $page, string $contentIdentifier, Request $request
    ) {
        $query = $page->contentIndices()
                      ->whereIdentifier($contentIdentifier);
        $queryString = $request->query();

        if (isset($queryString['remove_content'])) {
            if (isset($queryString['lang_id'])) {
                $contentIndex = $query->whereLangId($queryString['lang_id'])
                                      ->first();
                if ($contentIndex) {
                    $contentIndex->content->deleteContent($queryString);
                    $content = "deleted";
                } else {
                    $content = "no content found";
                }

            } else {
                $content = "no content index found";
            }


            return response()->json([
                'status'     => 'completed',
                'identifier' => $contentIdentifier,
                'content'    => $content
            ]);
        } else {
            $query->delete();

            return response()->json([
                'status'     => 'completed',
                'identifier' => $contentIdentifier
            ]);
        }

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
