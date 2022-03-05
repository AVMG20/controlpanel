<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;

class CheckoutController extends Controller
{
    /**
     * @param GeneralSettings $generalSettings
     * @return Factory|View|Application
     */
    public function index(GeneralSettings $generalSettings): Factory|View|Application
    {
        return view('checkout', [
            'credits_display_name' => $generalSettings->credits_display_name
        ]);
    }

    /**
     * Create a server on Pterodactyl
     *
     * @param Request $request
     * @return void
     */
    public function createServer(Request $request)
    {
        dd($request->all());
    }
}
