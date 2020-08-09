<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Models\Language;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class TranslationsController extends Controller
{
    public function index() {
        if( !auth('admin')->user()->hasPermission('index_translation')) {
            abort(403,
                  "Unauthorized action");
        }
        $path = base_path('resources/lang');
        $files = File::files($path);
        $languages = Language::active()->pluck('label',
                                               'code');

        return view('cms::admin.translations.index',
                    compact('files',
                            'languages'));
    }

    public function create() {
        $path = base_path('resources/lang');
        $fileNames = collect(File::files($path))->map(function(SplFileInfo $p) {
            return str_replace('.json',
                               '',
                               $p->getFileName());
        });

        $languages = Language::active()
                             ->whereNotIn('code',
                                          $fileNames)
                             ->where('is_default',
                                     false)
                             ->pluck('label',
                                     'code');


        return view('cms::admin.translations.create',
                    compact('languages'));
    }

    public function edit(Request $request) {
        if( !auth('admin')->user()->hasPermission('show_translation')) {
            abort(403,
                  "Unauthorized action");
        }
        $validatedData = $this->validate($request,
                                         [
                                             'filename' => 'required',
                                         ]);
        $path = base_path('resources/lang');
        $file = collect(File::files($path))->first(function(SplFileInfo $f) use ($validatedData) {
            return $f->getFileName() === $validatedData['filename'];
        });

        return view('cms::admin.translations.edit',
                    compact('file'));
    }

    public function store(Request $request) {
        if( !auth('admin')->user()->hasPermission('create_translation')) {
            abort(403,
                  "Unauthorized action");
        }
        $validatedData = $this->validate($request,
                                         [
                                             'code' => 'required',
                                         ]);
        $language = Language::where('code',
                                    $validatedData['code'])->first();

        if($language) {
            $path = base_path(sprintf('resources/lang/%s.json',
                                      $language->code));
            file_put_contents($path,
                              "{\n\n}");

            return redirect()->route('cms::admin.index.translations')
                             ->with('status',
                                    'New translation file created!');
        }

    }

    public function update(Request $request) {
        if( !auth('admin')->user()->hasPermission('edit_translation')) {
            abort(403,
                  "Unauthorized action");
        }
        $validatedData = $this->validate($request,
                                         [
                                             'filename' => 'required',
                                             'code'     => 'nullable',
                                         ]);
        $path = base_path('resources/lang');
        $file = collect(File::files($path))->first(function(SplFileInfo $f) use ($validatedData) {
            return $f->getFileName() === $validatedData['filename'];
        });

        file_put_contents($file,
                          $validatedData['code']);

        return redirect()->route('cms::admin.index.translations');
    }

    public function delete(Request $request) {
        if( !auth('admin')->user()->hasPermission('delete_translation')) {
            abort(403,
                  "Unauthorized action");
        }
        $validatedData = $this->validate($request,
                                         [
                                             'filename' => 'required',
                                         ]);
        $path = base_path('resources/lang');
        $file = collect(File::files($path))->first(function(SplFileInfo $f) use ($validatedData) {
            return $f->getFileName() === $validatedData['filename'];
        });

        unlink($file);

        return redirect()->route('cms::admin.index.translations')
                         ->withStatus('Translation file deleted!');
    }
}
