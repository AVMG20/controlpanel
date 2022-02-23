<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\PterodactylSettings;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PterodactylSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(PterodactylSettings $settings, Request $request)
    {
        $this->can('settings.pterodactyl.read');

        return view('settings.pterodactyl', compact('settings'));
    }

    /**
     * @param PterodactylSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(PterodactylSettings $settings, Request $request): RedirectResponse
    {
        $this->can('settings.pterodactyl.write');

        $request->validate([
            'pterodactyl_url' => 'required|string|url|max:191',
            'pterodactyl_api_key' => 'required|string|max:191'
        ]);

        $settings->pterodactyl_url = $request->pterodactyl_url;
        $settings->pterodactyl_api_key = $request->pterodactyl_api_key;

        $settings->save();

        return redirect()->back()->with('success', __('Settings saved'));
    }
}
