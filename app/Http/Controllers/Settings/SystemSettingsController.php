<?php

namespace App\Http\Controllers\Settings;

use App\Settings\SystemSettings;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(SystemSettings $settings, Request $request)
    {
        $this->checkPermission('settings.system.read');

        return view('settings.system', compact('settings'));
    }

    /**
     * @param SystemSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(SystemSettings $settings, Request $request): RedirectResponse
    {
        $this->checkPermission('settings.system.write');

        $request->mergeIfMissing([
            'register_ip_check' => 0,
            'creation_of_new_users' => 0,
            'creation_of_new_servers' => 0
        ]);
        
        $request->validate([
            'register_ip_check' => 'required|boolean',
            'creation_of_new_users' => 'required|boolean',
            'creation_of_new_servers' => 'required|boolean',
        ]);

        $settings->register_ip_check = $request->register_ip_check;
        $settings->creation_of_new_users = $request->creation_of_new_users;
        $settings->creation_of_new_servers = $request->creation_of_new_servers;

        $settings->save();

        return redirect()->back()->with('success', __('Settings saved'));

    }
}