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
    private function updateIcons(Request $request, CustomizationSettings $settings)
    {
        $request->validate([
            'icon' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'logo' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'favicon' => 'nullable|max:10000|mimes:ico',
        ]);

        if ($request->hasFile('icon')) {
            $filepath = storage_path("app/public/images/" . $settings->custom_icon_filename);
            //Delete our old File
            if (file_exists($filepath)) File::delete($filepath);
            //save the new image file as <timestamp>_logo.png to /public/images/.
            $filename = time() . "_icon.png";
            $request->file('icon')->storeAs('public/images', $filename);
            $settings->custom_icon_filename = $filename;
            $settings->save();
        }

        if ($request->hasFile('logo')) {
            $filepath = storage_path("app/public/images/" . $settings->custom_logo_filename);
            //Delete our old File
            if (file_exists($filepath)) File::delete($filepath);
            //save the new image file as <timestamp>_logo.png to /public/images/.
            $filename = time() . "_logo.png";
            $request->file('logo')->storeAs('public/images', $filename);
            $settings->custom_logo_filename = $filename;
            $settings->save();
        }

        if ($request->hasFile('favicon')) {
            $filepath = storage_path("app/public/images/" . $settings->custom_favicon_filename);
            //Delete our old File
            if (file_exists($filepath)) File::delete($filepath);
            //save the new image file as <timestamp>_logo.png to /public/images/.
            $filename = time() . "_favicon.ico";
            $request->file('favicon')->storeAs('public/images', $filename);
            $settings->custom_favicon_filename = $filename;
            $settings->save();
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
        $this->updateIcons($request, $settings);
        // update Javascript from request
        $this->updateCustomJavascript($request, $settings);

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
