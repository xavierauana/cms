<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Events\MenuDeleted;
use Anacreation\Cms\Events\MenuSaved;
use Anacreation\Cms\Models\Menu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Menu $menu) {
        $this->authorize('index', $menu);

        $menus = Menu::with([
            'links' => function ($query) {
                $query->whereParentId(0)->order();
            }
        ])->get();

        return view('cms::admin.menus.index', compact('menus'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Menu $menu) {
        $this->authorize('create', $menu);

        return view('cms::admin.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Menu $menuRepo) {
        $this->authorize('store', $menuRepo);
        $validatedData = $this->validate($request, [
            'name' => 'required',
            'code' => 'required|unique:menus',
        ]);

        $menuRepo->create($validatedData);

        return redirect("admin/menus");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Menu                $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu) {

        $this->authorize('update', $menu);
        $validatedData = $this->validate($request, [
            'name' => 'required',
            'code' => 'required|unique:menus,code,' . $menu->id,
        ]);

        $menu->update($validatedData);

        return redirect()->route('menus.index')
                         ->withStatus("{$menu->label} has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu) {

        $this->authorize('delete', $menu);

        $menu->delete();

        return redirect()->route('menus.index')
                         ->withStatus("{$menu->label} has been deleted!");

    }

    public function updateOrder(Request $request, Menu $menu) {

        $inputs = $request->all();
        foreach ($inputs as $data) {
            $link = $menu->links()->find($data['id']);
            if ($link) {
                $link->parent_id = $data['parentId'];
                $link->order = $data['order'];
                $link->save();
            }
        }

        return response()->json('completed');

    }
}
