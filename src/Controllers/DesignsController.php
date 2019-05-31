<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Enums\AdminPermissionAction;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Design;
use Anacreation\Cms\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class DesignsController extends Controller
{
    private $path;

    /**
     * DesignsController constructor.
     * @param $path
     */
    public function __construct() {
        $this->path = resource_path("views/theme");
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Anacreation\Cms\Models\Design $design
     * @param \Anacreation\Cms\Models\Page   $page
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */


    public function index(Design $design, Page $page) {

        $design = getDesignFiles();

        $pages = $page->get()->groupBy('template');

        return view('cms::admin.designs.index', compact('design', 'pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $type
     * @return \Illuminate\Http\Response
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    public function create(Request $request, string $type) {
        if ($type === 'definition') {
            if (!$request->user()->hasPermission('create_definition')) {
                throw new UnAuthorizedException();
            }
        } else {
            if (!$request->user()->hasPermission('create_layout')) {
                throw new UnAuthorizedException();
            }
        }

        return view("cms::admin.designs.create", compact('type'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request, string $type) {
        if ($type === 'definition') {
            if (!$request->user()->hasPermission('create_definition')) {
                throw new UnAuthorizedException();
            }
            $path = $this->getDefinitionFilePath($request, $type);
        } else {
            if (!$request->user()->hasPermission('create_layout')) {
                throw new UnAuthorizedException();
            }
            $path = $this->getFilePath($request, $type);
        }

        $path = $type === 'definition' ? $path . ".xml" : $path;
        $handle = fopen($path, "w");
        fwrite($handle, "");
        fclose($handle);


        $type = ucwords($type);

        $file = $request->get('file');

        return redirect()->route('designs.index')
                         ->withStatus("{$type} - {$file} is created!");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type) {

        if (!$request->ajax()) {
            return view('cms::admin.designs.edit',
                [
                    'file' => $request->get('file'),
                    'type' => $type,
                ]);
        }

        $path = $type === 'definition' ?
            $this->getDefinitionFilePath($request, $type) :
            $this->getFilePath($request, $type);

        $content = file_get_contents($path);

        return response()->json($content);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $type
     * @return \Illuminate\Http\Response
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    public function update(Request $request, string $type) {

        if ($type === 'definition') {
            if (!$request->user()->hasPermission('edit_definition')) {
                throw new UnAuthorizedException();
            }
            $path = $this->getDefinitionFilePath($request, $type);
        } else {
            if (!$request->user()->hasPermission('edit_layout')) {
                throw new UnAuthorizedException();
            }
            $path = $this->getFilePath($request, $type);
        }

        file_put_contents($path, $request->get('code'));

        Artisan::call('view:clear', ['--quiet' => true]);

        if ($request->ajax()) {
            return response()->json(['status' => 'completed']);
        }

        $type = ucwords($type);

        $file = $request->get('file');

        return redirect()->route('designs.index')
                         ->withStatus("{$type} - {$file} is updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $type
     * @return string
     */
    private function getFilePath(Request $request, string $type): string {

        $path = getActiveThemePath() . "/" . $type . "/" . $request->get('file') . ".blade.php";

        return $path;
    }

    private function getDefinitionFilePath(Request $request, string $type) {
        return getActiveThemePath() . "/" . $type . "/" . $request->get('file');
    }

    public function uploadLayout() {
        $view = $this->getUploadPage('layouts');

        return view($view);
    }

    public function postUploadLayout(Request $request) {
        $msg = $this->uploadFile('layouts', $request);

        return redirect()->route("designs.index")
                         ->withStatus($msg);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadDefinition() {
        $view = $this->getUploadPage('definition');

        return view($view);
    }

    public function postUploadDefinition(Request $request) {
        $msg = $this->uploadFile('definition', $request);

        return redirect()->route("designs.index")
                         ->withStatus($msg);
    }

    /**
     * @param string $type
     * @return string
     */
    private function getUploadPage(string $type): string {
        switch ($type) {
            case 'layouts';
            case 'definition':
                $permissionCode = "upload_" . ($type === 'layouts' ? 'layout' : 'definition');
                if (!request()->user()
                              ->hasPermission($permissionCode)) {
                    abort(403);
                };

                return "cms::admin.designs.upload." . ($type === 'layouts' ? 'layout' : 'definition');

            default:
                abort(403);

        }
    }

    /**
     * @param string                   $type
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function uploadFile(string $type, Request $request): string {
        switch ($type) {
            case 'layouts';
            case 'definition':
                $permissionCode = AdminPermissionAction::Create()
                                                       ->getValue() . "_" . ($type === 'layouts' ? 'layout' : 'definition');
                if (!request()->user()
                              ->hasPermission($permissionCode)) {
                    abort(403);
                };

                $this->registerValidationRule();

                $this->validate($request, [
                    'files'   => 'required',
                    'files.*' => 'file|' . ($type === 'layouts' ? 'isBladeFile' : 'isXml')
                ]);

                $files = $request->file('files');

                $layoutPath = getActiveThemePath() . "/" . $type;
                collect($files)->each(function (UploadedFile $file) use (
                    $layoutPath
                ) {
                    $file->move($layoutPath, $file->getClientOriginalName());
                });

                return 'File uploaded!';

            default:
                abort(403);

        }


        $msg = 'Layout uploaded!';

        return $msg;
    }

    private function registerValidationRule(): void {
        Validator::extend('isBladeFile',
            function ($attribute, $value, $parameters, $validator) {
                $nameArray = explode('.', $value->getClientOriginalName());

                $length = count($nameArray);

                if ($length < 3) {
                    return false;
                }

                if ($nameArray[$length - 1] !== 'php') {
                    return false;
                }
                if ($nameArray[$length - 2] !== 'blade') {
                    return false;
                }

                return true;

            });

        Validator::extend('isXml',
            function ($attribute, $value, $parameters, $validator) {
                /**
                 * @var \Illuminate\Http\UploadedFile $value
                 */
                $extension = $value->getClientOriginalExtension();

                return $extension === 'xml';

            });
    }
}
