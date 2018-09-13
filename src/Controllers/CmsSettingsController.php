<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Services\SettingService;
use App\Http\Controllers\Controller;

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

        return view('cms::admin.settings.index');
    }
}
