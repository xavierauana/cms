<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Enums\DesignType;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Design;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\Design\CreateTemplateFile;
use Anacreation\Cms\Services\Design\GetTemplateContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DesignsController extends CmsAdminBaseController
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

        return view('cms::admin.designs.index',
                    compact('design',
                            'pages'));
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
        if($type === 'definition') {
            if( !$request->user()->hasPermission('create_definition')) {
                throw new UnAuthorizedException();
            }
        } else {
            if( !$request->user()->hasPermission('create_layout')) {
                throw new UnAuthorizedException();
            }
        }

        return view("cms::admin.designs.create",
                    compact('type'));

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

        $this->checkPermission($type,
                               'create');

        $service->execute(
            new DesignType($type),
            $request->get('file'));

        $msg = sprintf("%s - %s is created!",
                       ucwords($type),
                       $request->get('file'));

        return redirect()->route('designs.index')
                         ->withStatus($msg);
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

        if( !$request->ajax()) {
            return view('cms::admin.designs.edit',
                        [
                            'file' => $request->get('file'),
                            'type' => $type,
                        ]);
        }

        $content = $service->execute(
            new DesignType($type),
            $request->get('file'));

        return response()->json($content);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                               $request
     * @param string                                                 $type
     * @param \Anacreation\Cms\Services\Design\UpdateTemplateContent $service
     * @param \Anacreation\Cms\Services\ReloadPhpFpm                 $fpm
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(
        Request $request,
        string $type,
        UpdateTemplateContent $service,
        ReloadPhpFpm $fpm
    ) {
        $this->authorize(CmsAction::Edit(),
                         ucwords((Str::singular($type))));

        $service->execute(
            new DesignType($type),
            $request->get('file'),
            $request->get('code'));

        Artisan::call('view:clear',
                      ['--quiet' => true]);

        $fpm->reload();

        if($request->ajax()) {
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
     * @param int
     * $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function uploadLayout() {
        $type = DesignType::Layout();

        $this->checkUploadPermission($type);

        $view = $this->getUploadPage($type);

        return view($view);
    }

    public function postUploadLayout(Request $request) {
        $type = DesignType::Layout();

        $this->checkUploadPermission($type);

        $msg = $this->uploadFile('layouts',
                                 $request);

        return redirect()->route("designs.index")
                         ->withStatus($msg);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadDefinition() {
        $type = DesignType::Definition();

        $this->checkUploadPermission($type);
        $view = $this->getUploadPage($type);

        return view($view);
    }

    public function postUploadDefinition(Request $request) {
        $type = DesignType::Definition();

        $this->checkUploadPermission($type);

        $msg = $this->uploadFile('definition',
                                 $request);

        return redirect()->route("designs.index")
                         ->withStatus($msg);
    }

    /**
     * @param \Anacreation\Cms\Enums\DesignType $type
     * @return \Anacreation\Cms\Enums\DesignType
     */
    private function getUploadPage(DesignType $type
    ): string {
        switch($type) {
            case DesignType::Layout();
                return "cms::admin.designs.upload.layout";
            default:
                return "cms::admin.designs.upload.definition";
        }
    }

    /**
     * @param string                   $type
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function uploadFile(DesignType $type, Request $request): string {
        $this->registerValidationRule();

        $rules = [
            'files'   => 'required',
            'files.*' => 'file',
        ];

        switch($type) {
            case DesignType::Layout();
                $rules['files.*'] .= "|isBladeFile";
                $directory = 'layouts';
                break;
            default:
                $rules['files.*'] .= "|isXml";
                $directory = 'definition';
        }

        $this->validate($request,
                        $rules);

        $files = $request->file('files');

        $layoutPath = getActiveThemePath()."/".$directory;
        collect($files)->each(function(UploadedFile $file) use (
            $layoutPath
        ) {
            $file->move($layoutPath,
                        $file->getClientOriginalName());
        });

        return 'File uploaded!';

    }

    private function registerValidationRule(): void {
        Validator::extend('isBladeFile',
            function($attribute, $value, $parameters, $validator) {
                $nameArray = explode('.',
                                     $value->getClientOriginalName());

                $length = count($nameArray);

                if($length < 3) {
                    return false;
                }

                if($nameArray[$length - 1] !== 'php') {
                    return false;
                }
                if($nameArray[$length - 2] !== 'blade') {
                    return false;
                }

                return true;

            });

        Validator::extend('isXml',
            function($attribute, $value, $parameters, $validator) {
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

        $type = ($type === 'definition' ? 'definition': 'layout');

        $permission = "{
            $action}_{
            $type}";

        if( !request()->user()->hasPermission($permission)) {
            throw new UnAuthorizedException();
        }
    }

    /**
     * @param \Anacreation\Cms\Enums\DesignType $type
     */
    private
    function checkUploadPermission(
        DesignType $type
    ): void {
        switch($type) {
            case DesignType::Layout();
                $permissionCode = LayoutPermission::Upload();
                break;
            default:
                $permissionCode = DefinitionPermission::Upload();
        }
        if( !request()->user()
                      ->hasPermission($permissionCode->getValue())) {
            throw new AuthorizationException('Unauthorized');
        };
    }
}
