<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\CustomizationSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CustomizationSettingsController extends Controller
{
    protected CustomizationSettings $customizationSettings;

    public function __construct(CustomizationSettings $settings)
    {
        $this->customizationSettings = $settings;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->checkPermission('settings.customization.read');

        $jscode = Storage::disk('public')->get('js/' . $this->customizationSettings->custom_js_filename);

        $settings = $this->customizationSettings;

        return view('settings.customization', compact('settings', 'jscode'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
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
            'icon' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'logo' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'favicon' => 'nullable|max:10000|mimes:jpg,png,jpeg',
        ]);

        // update Icons from request
        $this->updateIcons($request);
        // update Javascript from request
        $this->updateCustomJavascript($request);

        $this->customizationSettings->primary_color = $request->primary_color;
        $this->customizationSettings->secondary_color = $request->secondary_color;
        $this->customizationSettings->tertiary_color = $request->tertiary_color;
        $this->customizationSettings->text_color = $request->text_color;
        $this->customizationSettings->logo_enabled = $request->logo_enabled;
        $this->customizationSettings->alert_enabled = $request->alert_enabled;
        $this->customizationSettings->alert_message = $request->alert_message;
        $this->customizationSettings->alert_type = $request->alert_type;
        $this->customizationSettings->save();

        return redirect()->back()->with('success', __('Settings saved'));
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function updateIcons(Request $request): void
    {
        //update icon
        if ($request->hasFile('icon')) {
            $fileName = $this->updateFile($request, 'icon');
            //update settings
            $this->customizationSettings->custom_icon_filename = $fileName;
            $this->customizationSettings->save();
        }

        //update logo
        if ($request->hasFile('logo')) {
            $fileName = $this->updateFile($request, 'logo');
            //update settings
            $this->customizationSettings->custom_logo_filename = $fileName;
            $this->customizationSettings->save();
        }

        //update favicon
        if ($request->hasFile('favicon')) {
            $fileName = $this->updateFile($request, 'favicon');
            //update settings
            $this->customizationSettings->custom_favicon_filename = $fileName;
            $this->customizationSettings->save();
        }

        Artisan::call("cache:clear");
        Artisan::call("view:clear");
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function updateCustomJavascript(Request $request): void
    {
        //delete old javascript file
        if (Storage::disk('public')->exists('js/' . $this->customizationSettings->custom_js_filename)) {
            Storage::disk('public')->delete('js/' . $this->customizationSettings->custom_js_filename);
        }

        //if there is no code submitted, return
        if (!$request->jscode) return;

        //save the new javascript. This file is loaded in the Header of /view/layouts/dashboard.blade.php
        $filename = uniqid() . ".js";
        Storage::disk('public')->put('js/' . $filename, $request->jscode);

        $this->customizationSettings->custom_js_filename = $filename;
        $this->customizationSettings->save();
    }

    /**
     * @param Request $request
     * @param string $key
     * @return string
     */
    protected function updateFile(Request $request, string $key): string
    {
        $path = storage_path("app/public/images/");

        switch ($key) {
            case "icon":
                $path .= $this->customizationSettings->custom_icon_filename;
                break;
            case "logo":
                $path .= $this->customizationSettings->custom_logo_filename;
                break;
            case "favicon":
                $path .= $this->customizationSettings->custom_favicon_filename;
                break;
        }

        //delete existing file
        if (file_exists($path)) File::delete($path);

        //store file
        $filename = uniqid() . '.' . $request->file($key)->extension();
        $request->file($key)->storeAs('public/images', $filename);

        return $filename;
    }
}
