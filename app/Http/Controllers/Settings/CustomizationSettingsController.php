<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\CustomizationSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class CustomizationSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(CustomizationSettings $settings, Request $request)
    {
        $this->checkPermission('settings.customization.read');

        $jscode = Storage::disk('open')->get('js/custom.js');

        return view('settings.customization', compact('settings', 'jscode'));
    }

    private function updateIcons(Request $request)
    {
        $request->validate([
            'icon' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'logo' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'favicon' => 'nullable|max:10000|mimes:ico',
        ]);

        if ($request->hasFile('icon')) {
            $request->file('icon')->storeAs('public/images', 'icon.png');
        }
        if ($request->hasFile('logo')) {
            $request->file('logo')->storeAs('public/images', 'logo.png');
        }
        if ($request->hasFile('favicon')) {
            $request->file('favicon')->storeAs('public/images', 'favicon.ico');
        }
    }

    private function updateCustomJavascript(Request $request)
    {
        //if no code is entered, clear the file
        ($request->jscode) ? : $request->jscode = "";

        Storage::disk('open')->put('js/custom.js', $request->jscode);
    }


    /**
     * @param CustomizationSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(CustomizationSettings $settings, Request $request): RedirectResponse
    {
        $this->checkPermission('settings.customization.write');

        $request->validate([
            'primary_color' => [
                'required',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'secondary_color' => [
                'required',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'tertiary_color' => [
                'required',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'logo_enabled' => 'boolean',
            'alert_enabled' => 'boolean',
            'alert_message' => 'nullable',
            'alert_type' => 'required',
        ]);

        // update Icons from request
        $this->updateIcons($request);
        // update Javascript from request
        $this->updateCustomJavascript($request);

        $settings->primary_color = $request->primary_color;
        $settings->secondary_color = $request->secondary_color;
        $settings->tertiary_color = $request->tertiary_color;
        $settings->text_color = $request->text_color;
        $settings->logo_enabled = $request->logo_enabled;
        $settings->alert_enabled = $request->alert_enabled;
        $settings->alert_message = $request->alert_message;
        $settings->alert_type = $request->alert_type;
        $settings->save();

        return redirect()->back()->with('success', __('Settings saved'));
    }

}