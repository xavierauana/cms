<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Models\Design;
use Anacreation\Cms\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */


    public function index(Design $design, Page $page) {

        $this->authorize('index', $design);
        $design = getDesignFiles();
        $pages = $page->get()->groupBy('template');


        return view('cms::admin.designs.index', compact('design', 'pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type) {

        if ($request->ajax()) {

            $path = $this->getFilePath($request, $type);

            $content = file_get_contents($path);

            return response()->json($content);
        }

        return view('cms::admin.designs.edit',
            ['file' => $request->get('file')]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string                    $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type) {
        if ($request->ajax()) {
            $path = $this->getFilePath($request, $type);

            file_put_contents($path, $request->get('code'));

            return response()->json(['status' => 'completed']);
        }
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

}
