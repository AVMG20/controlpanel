<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(GeneralSettings $settings, Request $request)
    {
        $this->checkPermission('settings.general.read');

        return view('settings.general', compact('settings'));
    }

    /**
     * @param GeneralSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(GeneralSettings $settings, Request $request): RedirectResponse
    {
        $this->checkPermission('settings.general.write');

        $request->validate([
            'credits_display_name' => 'required|string|max:191'
        ]);

        $settings->credits_display_name = $request->credits_display_name;

        $settings->save();

        return redirect()->back()->with('success', __('Settings saved'));
    }
}
