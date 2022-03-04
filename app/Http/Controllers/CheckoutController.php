<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Settings\GeneralSettings;

class CheckoutController extends Controller
{
    public function index(GeneralSettings $generalSettings)
    {
        $configurations = Configuration::query()->with(['eggs' , 'locations'])->get();

        return view('checkout', [
            'configurations' => $configurations,
            'credits_display_name' => $generalSettings->credits_display_name
        ]);
    }
}
