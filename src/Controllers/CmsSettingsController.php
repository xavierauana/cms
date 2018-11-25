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

        $settings = $this->service->all();

        return view('cms::admin.settings.index', compact('settings'));
    }

    public function edit(int $settingId) {

        $setting = $this->service->find($settingId);

        return view("cms::admin.settings.edit", compact('setting'));
    }

    public function update(Request $request, int $settingId) {

        $validatedData = $this->validate($request, [
            'label' => 'required',
            'value' => 'required',
        ]);

        $this->service->update($settingId, $validatedData);

        return redirect()->route('settings.index')
                         ->withStatus('Setting updated');
    }
}
