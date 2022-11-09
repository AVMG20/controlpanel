<?php

namespace App\Http\Controllers\User;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Exceptions\PterodactylRequestException;
use App\Helper\PterodactylServerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Server\ServerStoreRequest;
use App\Models\Server;
use App\Settings\GeneralSettings;
use App\Settings\SystemSettings;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class CheckoutController extends Controller
{
    /**
     * @param GeneralSettings $generalSettings
     * @return Factory|View|Application
     */
    public function index(GeneralSettings $generalSettings, SystemSettings $systemSettings): Factory|View|Application|RedirectResponse
    {
        if (!$systemSettings->creation_of_new_servers && !$this->can(Permission::all())) {
            return redirect()->back()->with('error', __('Creation of new servers has been disabled by the system administrator'));
        }

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

        return redirect()->route('dashboard.index')->with('success', __('Server created successfully!'));
    }
}
