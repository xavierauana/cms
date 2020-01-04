<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Entities\ContentObject;
use Anacreation\Cms\Models\CommonContent;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Services\ContentService;
use Anacreation\Cms\Services\LanguageService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonContentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Anacreation\Cms\Models\CommonContent $commonContent
     *
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(CommonContent $commonContent, Request $request) {
        $this->authorize('index',
                         $commonContent);
        $commonContents = $commonContent->searchable($request->query('keyword'))
                                        ->sortable()
                                        ->latest()
                                        ->paginate();

        return view('cms::admin.common_contents.index',
                    compact('commonContents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\CommonContent $commonContent
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(CommonContent $commonContent, LanguageService $languageService) {
        $this->authorize('create',
                         $commonContent);

        $languages = $languageService->activeLanguages;

        return view('cms::admin.common_contents.create',
                    compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @param \Illuminate\Http\Request              $request
     * @param \Anacreation\Cms\Models\CommonContent $commonContent
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, CommonContent $commonContent) {

        $this->authorize('create',
                         $commonContent);

        $validatedInputs = $this->validate($request,
                                           [
                                               'label'             => 'required',
                                               'type'              => 'required|boolean',
                                               'key'               => 'required|unique:common_contents,key',
                                               'content.*.lang_id' => 'required:in:'.implode(",",
                                                                                             Language::pluck('id')
                                                                                                     ->toArray()),
                                               'content.*.content' => 'nullable',
                                           ]);


        $service = new ContentService();

        DB::beginTransaction();

        try {

            /** @var CommonContent $newContent */
            $newContent = $commonContent->create($validatedInputs);

            foreach($validatedInputs['content'] as $data) {
                $contentObject = new ContentObject(CommonContent::Identifier,
                                                   $data['lang_id'],
                                                   $data['content'],
                                                   'text');
                $service->updateOrCreateContentIndexWithContentObject($newContent,
                                                                      $contentObject);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


        return redirect()->route('cms::common_contents.index')->withStatus('New Content Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param CommonContent $commonContent
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CommonContent $commonContent) {
        $this->authorize('show',
                         $commonContent);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CommonContent                             $commonContent
     *
     * @param \Anacreation\Cms\Services\LanguageService $languageService
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(CommonContent $commonContent, LanguageService $languageService) {
        $this->authorize('edit',
                         $commonContent);
        $languages = $languageService->activeLanguages;


        return view('cms::admin.common_contents.edit',
                    compact('commonContent',
                            'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request              $request
     * @param \Anacreation\Cms\Models\CommonContent $commonContent
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, CommonContent $commonContent) {
        $this->authorize('edit',
                         $commonContent);

        $this->authorize('create',
                         $commonContent);

        $validatedInputs = $this->validate($request,
                                           [
                                               'label'             => 'required',
                                               'type'              => 'required|boolean',
                                               'key'               => 'required|unique:common_contents,key,'.
                                                                      $commonContent->id,
                                               'content.*.lang_id' => 'required:in:'.implode(",",
                                                                                             Language::pluck('id')
                                                                                                     ->toArray()),
                                               'content.*.content' => 'nullable',
                                           ]);


        $service = new ContentService();


        $commonContent->update($validatedInputs);

        foreach($validatedInputs['content'] as $data) {
            $contentObject = new ContentObject(CommonContent::Identifier,
                                               $data['lang_id'],
                                               $data['content'],
                                               'text');
            $service->updateOrCreateContentIndexWithContentObject($commonContent,
                                                                  $contentObject);
        }


        return redirect()->route('cms::common_contents.index')
                         ->withStatus('Common content updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CommonContent $commonContent
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(CommonContent $commonContent) {
        $this->authorize('delete',
                         $commonContent);

        $commonContent->delete();

        return response()->json("Content: {$commonContent->id} deleted!");
    }

}
