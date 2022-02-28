<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;

class CheckoutController extends Controller
{
    public function index(GeneralSettings $generalSettings)
    {
        return view('checkout', [
            'credits_display_name' => $generalSettings->credits_display_name
        ]);
    }
}
