<?php

namespace App\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelSettings\Settings;

class CustomizationSettings
{
    //url location of the icon
    public string $panel_icon_url;
    //url location of the favicon
    public string $panel_favicon_url;

    public static function group(): string
    {
        return 'customization';
    }

    private function updateIcons(Request $request)
    {
        $request->validate([
            'icon' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'logo' => 'nullable|max:10000|mimes:jpg,png,jpeg',
            'favicon' => 'nullable|max:10000|mimes:ico',
        ]);

        if ($request->hasFile('icon')) {
            $request->file('icon')->storeAs('public', 'icon.png');
        }
        if ($request->hasFile('logo')) {
            $request->file('logo')->storeAs('public', 'logo.png');
        }
        if ($request->hasFile('favicon')) {
            $request->file('favicon')->storeAs('public', 'favicon.ico');
        }
    }

    private function updateCustomJavascript(Request $request)
    {
        Storage::disk('open')->put('js/custom.js', $request->jscode);
    }

}
