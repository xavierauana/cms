<?php

namespace Anacreation\CMS\Controllers;

use Anacreation\Cms\Models\Language;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class LanguagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Anacreation\Cms\Models\Language $language
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Language $language) {
        $this->authorize('index', $language);

        $languages = $language->all();

        return view('cms::admin.languages.index', compact("languages"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\Language $language
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Language $language) {
        $this->authorize('create', $language);

        $languages = Language::where('id', "<>", $language->id)
                             ->pluck('label', 'id')->toArray();

        $languages = array_merge(['0' => 'To Default'], $languages);

        return view('cms::admin.languages.create', compact('languages'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, Language $language) {
        $this->authorize('create', $language);

        $validateData = $this->validate($request, [
            'label'                => 'required',
            'code'                 => 'required|unique:languages',
            'is_active'            => 'required|boolean',
            'is_default'           => 'required|boolean',
            'fallback_language_id' => 'required|in:0,' . implode(',',
                    Language::pluck('id')->toArray()),
        ]);

        if ($validateData['is_default']) {
            Language::all()->each(function (Language $language) {
                $language->is_default = false;
                $language->save();
            });
        }

        $language->create($validateData);

        return redirect()->route('languages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Language $language
     * @return Response
     */
    public function show(Language $language) {
        $this->authorize('show', $language);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Language $language
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Language $language) {
        $this->authorize('edit', $language);
        $languages = Language::where('id', "<>", $language->id)
                             ->pluck('label', 'id')->toArray();
        $languages = array_merge(['0' => 'To Default'], $languages);


        return view('cms::admin.languages.edit',
            compact("language", 'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Language $language
     * @return Response
     */
    public function update(Request $request, Language $language) {
        $this->authorize('edit', $language);

        $validateData = $this->validate($request, [
            'label'                => 'required',
            'code'                 => [
                'required',
                Rule::unique('languages')->ignore($language->id, 'id')
            ],
            'is_active'            => 'required|boolean',
            'is_default'           => 'required|boolean',
            'fallback_language_id' => 'required|in:0,' . implode(',',
                    Language::pluck('id')->toArray()),
        ]);


        if ($validateData['is_default'] == "1") {
            Language::where('id', '<>', $language->id)->get()->each(function (
                Language $language
            ) {
                $language->is_default = false;
                $language->save();
            });
        }

        $language->update($validateData);

        return redirect()->route('languages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Language $language
     * @return Response
     * @throws \Exception
     */
    public function destroy(Language $language) {
        $this->authorize('delete', $language);

        $language->delete();

        return response()->json(['status' => 'completed']);
    }
}
