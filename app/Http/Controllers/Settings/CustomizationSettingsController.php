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
    public function index(CustomizationSettings $settings)
    {
        $this->checkPermission('settings.customization.read');

        $jscode = Storage::disk('open')->get('js/' . $settings->custom_js_filename);

        return view('settings.customization', compact('settings', 'jscode'));
    }

    /**
     * @param Request $request
     * @return void
     */
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

    /**
     * @param CustomizationSettings $settings
     * @param Request $request
     * @return void
     */
    private function updateCustomJavascript(Request $request, CustomizationSettings $settings)
    {
        //if no code is entered, clear the file
        ($request->jscode) ?: $request->jscode = "";

        //Delete our old File
        if (file_exists(public_path("js/" . $settings->custom_js_filename))) {
            File::delete(public_path("js/" . $settings->custom_js_filename));
        }

        //save the new javascript file as <timestamp>.js to /public/js/. This file is loaded in the Header of /view/layouts/dashboard.blade.php
        $filename = time() . ".js";
        Storage::disk('open')->put('js/' . $filename, $request->jscode);

        $settings->custom_js_filename = $filename;
        $settings->save();

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
                'nullable',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'secondary_color' => [
                'nullable',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'tertiary_color' => [
                'nullable',
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
        $this->updateCustomJavascript($request, $settings);

        $settings->primary_color = $request->primary_color ?? "#F2F4F6";
        $settings->secondary_color = $request->secondary_color ?? "#FFFFFF";
        $settings->tertiary_color = $request->tertiary_color ?? "#1F2937";
        $settings->text_color = $request->text_color ?? "#111827";
        $settings->logo_enabled = $request->logo_enabled;
        $settings->alert_enabled = $request->alert_enabled;
        $settings->alert_message = $request->alert_message;
        $settings->alert_type = $request->alert_type;
        $settings->save();

        return redirect()->back()->with('success', __('Settings saved'));
    }

}
