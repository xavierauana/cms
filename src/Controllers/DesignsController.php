<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Enums\AdminPermissionAction;
use Anacreation\Cms\Enums\DesignType;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Design;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\Design\CreateTemplateFile;
use Anacreation\Cms\Services\Design\GetTemplateContent;
use Anacreation\Cms\Services\Design\UpdateTemplateContent;
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
     * @param \Illuminate\Http\Request                               $request
     * @param string                                                 $type
     * @param \Anacreation\Cms\Services\Design\UpdateTemplateContent $service
     * @return void
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    public function store(
        Request $request, string $type, CreateTemplateFile $service
    ) {

        $this->checkPermission($type, 'create');

        $service->execute(
            ($type === 'definition' ? DesignType::Definition() : DesignType::Layout()),
            $request->get('file'));

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
    public function edit(
        Request $request, string $type, GetTemplateContent $service
    ) {

        if (!$request->ajax()) {
            return view('cms::admin.designs.edit',
                [
                    'file' => $request->get('file'),
                    'type' => $type,
                ]);
        }

        $content = $service->execute(
            ($type === 'definition' ? DesignType::Definition() : DesignType::Layout()),
            $request->get('file'));

        return response()->json($content);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                               $request
     * @param string                                                 $type
     * @param \Anacreation\Cms\Services\Design\UpdateTemplateContent $service
     * @return \Illuminate\Http\Response
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    public function update(
        Request $request, string $type, UpdateTemplateContent $service
    ) {

        $this->checkPermission($type, 'edit');

        $service->execute(
            ($type === 'definition' ? DesignType::Definition() : DesignType::Layout()),
            $request->get('file'),
            $request->get('code'));


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

    /**
     * @param string $type
     * @param string $action
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    private function checkPermission(string $type, string $action) {
        $type = ($type === 'definition' ? 'definition' : 'layout');

        $permission = "{$action}_{$type}";

        if (!request()->user()->hasPermission($permission)) {
            throw new UnAuthorizedException();
        }
    }
}
