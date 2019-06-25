<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Services\SettingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CmsSettingsController extends Controller
{
    /**
     * @var \Anacreation\Cms\Services\SettingService
     */
    private $service;

    /**
     * CmsSettingsController constructor.
     * @param \Anacreation\Cms\Services\SettingService $service
     */
    public function __construct(SettingService $service) {
        $this->service = $service;
    }

    public function index() {

        $this->authorize('index', 'CmsSettings');

        $settings =DB::table(SettingService::tableName)
                     ->get();

        return view('cms::admin.settings.index', compact('settings'));
    }

    public function create() {

        $this->authorize('create', 'CmsSettings');

        return view('cms::admin.settings.create');
    }

    public function store(Request $request) {

        $this->authorize('create', 'CmsSettings');

        $validatedData = $this->validate($request, [
            'label' => 'required',
            'key'   => 'required|unique:cms_settings',
            'value' => 'nullable',
        ]);

        $this->service->create($validatedData);

        return redirect()->route('settings.index')
                         ->withStatus('New setting created.');
    }

    public function edit(int $settingId) {

        $this->authorize('edit', 'CmsSettings');

        $setting = $this->service->find($settingId);

        return view("cms::admin.settings.edit", compact('setting'));
    }

    public function update(Request $request, int $settingId) {

        $this->authorize('edit', 'CmsSettings');

        $validatedData = $this->validate($request, [
            'label' => 'required',
            'value' => 'nullable',
        ]);

        $this->service->update($settingId, $validatedData);

        return redirect()->route('settings.index')
                         ->withStatus('Setting updated');
    }

    public function destroy(Request $request, int $settingId) {

        $this->authorize('delete', 'CmsSettings');

        $this->service->delete($settingId);

        return redirect()->route('settings.index')
                         ->withStatus('Setting is deleted!');
    }
}
