<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Services\SettingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        $settings = $this->service->all();

        return view('cms::admin.settings.index', compact('settings'));
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
}
