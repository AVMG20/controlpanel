<?php

namespace App\Http\Controllers\Settings;

use App\Enums\NavigationLocation;
use App\Http\Controllers\Controller;
use App\Models\Navigation;
use App\Settings\GeneralSettings;
use Controlpanel\Vouchers\Models\Voucher;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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

        $roles = Role::all();

        return view('settings.general', compact('settings', 'roles'));
    }

    /**
     * @param GeneralSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(GeneralSettings $settings, Request $request): RedirectResponse
    {
        $this->checkPermission('settings.general.write');

        //convert null to empty string
        if (is_null($request->main_site)){
            $request->merge(['main_site' => '']);
        }

        $request->validate([
            'main_site' => 'string|max:191',
            'credits_display_name' => 'required|string|max:191',
            'initial_user_credits' => 'required|numeric|min:0|max:99999999999',
            'initial_server_limit' => 'required|numeric|min:0|max:99999999999',
            'initial_user_role' => 'required|exists:roles,id',
        ]);

        $settings->credits_display_name = $request->credits_display_name;
        $settings->main_site = $request->main_site;
        $settings->initial_user_credits = $request->initial_user_credits;
        $settings->initial_server_limit = $request->initial_server_limit;
        $settings->initial_user_role = $request->initial_user_role;

        $settings->save();

        return redirect()->back()->with('success', __('Settings saved'));
    }
}
