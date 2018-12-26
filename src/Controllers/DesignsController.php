<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Design;
use Anacreation\Cms\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

        //        $this->authorize('index', $design);
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
            if (!$request->user()->hasPermission('update_layout')) {
                throw new UnAuthorizedException();
            }
        }

        return view("cms::admin.designs.create", compact('type'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request, string $type) {
        if ($type === 'definition') {
            if (!$request->user()->hasPermission('store_definition')) {
                throw new UnAuthorizedException();
            }
            $path = $this->getDefinitionFilePath($request, $type);
        } else {
            if (!$request->user()->hasPermission('store_layout')) {
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
     * @param  int $id
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
     * @param  \Illuminate\Http\Request $request
     * @param string                    $type
     * @return \Illuminate\Http\Response
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    public function update(Request $request, string $type) {

        if ($type === 'definition') {
            if (!$request->user()->hasPermission('update_definition')) {
                throw new UnAuthorizedException();
            }
            $path = $this->getDefinitionFilePath($request, $type);
        } else {
            if (!$request->user()->hasPermission('update_layout')) {
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
     * @param  int $id
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

}
