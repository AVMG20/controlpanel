<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use App\Settings\ServerSettings;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServerSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ServerSettings $settings
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(ServerSettings $settings, Request $request): View|Factory|Application
    {
        $this->checkPermission('settings.server.read');

        return view('settings.server', compact('settings'));
    }

    /**
     * @param ServerSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(ServerSettings $settings, Request $request): RedirectResponse
    {
        $this->checkPermission('settings.server.write');

        $request->validate([
            'credits_display_name' => 'required|string|max:191'
        ]);

        $settings->credits_display_name = $request->credits_display_name;

        $settings->save();

        return redirect()->back()->with('success', __('Settings saved'));
    }
}
