<?php

namespace App\Http\Controllers;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Exceptions\PterodactylRequestException;
use App\Helper\PterodactylServerHelper;
use App\Http\Requests\Server\ServerStoreRequest;
use App\Models\Server;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ServerController extends Controller
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
     * @param ServerStoreRequest $request
     * @param PterodactylServerHelper $pterodactylServerHelper
     * @param PterodactylClient $client
     * @return RedirectResponse
     */
    public function createServer(ServerStoreRequest $request, PterodactylServerHelper $pterodactylServerHelper, PterodactylClient $client)
    {
        $data = $pterodactylServerHelper->createServerData(
            $request->name,
            $request->user(),
            $request->egg,
            $request->location,
            $request->configuration,
        );

        try {
            $response = $client->createServer($data);
            Server::createFromPterodactylResponse($response, $request->user(), $request->configuration->price);
        } catch (PterodactylRequestException $exception) {
            logger('Creating server failed', ['exception' => $exception]);
            return redirect()->back()->with('error', $exception->getPterodactylErrorMessage());
        } catch (Exception $exception) {
            logger('Creating server failed', ['exception' => $exception]);
            return redirect()->back()->with('error', __('Unexpected error during server creation'));
        }

        return redirect()->route('dashboard')->with('success', __('Server created successfully!'));
    }



}
