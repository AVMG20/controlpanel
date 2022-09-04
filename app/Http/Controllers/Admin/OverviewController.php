<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Http\Controllers\Controller;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Models\Pterodactyl\Nest;
use App\Models\Pterodactyl\Node;
use App\Models\Server;
use App\Models\User;
use App\Settings\GeneralSettings;

class OverviewController extends Controller
{
    public const TTL = 86400;

    public function index(PterodactylClient $pterodactylClient)
    {
        /** @var GeneralSettings $settings */
        $settings = app(GeneralSettings::class);

        //Get counters
        $counters = collect();
        //Set basic variables in the collection
        $counters->put('users', User::query()->count());
        $counters->put('credits', number_format(User::query()->sum('credits'), 2, '.', ''));
       // $counters->put('payments', Payment::query()->count());
        $counters->put('eggs', Egg::query()->count());
        $counters->put('nests', Nest::query()->count());
        $counters->put('locations', Location::query()->count());

        //Prepare for counting
        $counters->put('servers', collect());
        $counters['servers']->active = 0;
        $counters['servers']->total = 0;
        $counters->put('earnings', collect());
        $counters['earnings']->active = 0;
        $counters['earnings']->total = 0;
        $counters->put('totalUsagePercent', 0);

        /*
        //Prepare subCollection 'payments'
        $counters->put('payments', collect());
        //Get and save payments from last 2 months for later filtering and looping$payments = Payment::query()->where('created_at', '>=', Carbon::today()->startOfMonth()->subMonth())->where('status', 'paid')->get();
        //Prepare collections and set a few variables
        $counters['payments']->put('thisMonth', collect());
        $counters['payments']->put('lastMonth', collect());
        $counters['payments']['thisMonth']->timeStart = Carbon::today()->startOfMonth()->toDateString();
        $counters['payments']['thisMonth']->timeEnd = Carbon::today()->toDateString();
        $counters['payments']['lastMonth']->timeStart = Carbon::today()->startOfMonth()->subMonth()->toDateString();
        $counters['payments']['lastMonth']->timeEnd = Carbon::today()->endOfMonth()->subMonth()->toDateString();


        //Fill out variables for each currency separately
        foreach ($payments->where('created_at', '>=', Carbon::today()->startOfMonth()) as $payment) {
            $paymentCurrency = $payment->currency_code;
            if (!isset($counters['payments']['thisMonth'][$paymentCurrency])) {
                $counters['payments']['thisMonth']->put($paymentCurrency, collect());
                $counters['payments']['thisMonth'][$paymentCurrency]->total = 0;
                $counters['payments']['thisMonth'][$paymentCurrency]->count = 0;
            }
            $counters['payments']['thisMonth'][$paymentCurrency]->total += $payment->total_price;
            $counters['payments']['thisMonth'][$paymentCurrency]->count++;
        }
        foreach ($payments->where('created_at', '<', Carbon::today()->startOfMonth()) as $payment) {
            $paymentCurrency = $payment->currency_code;
            if (!isset($counters['payments']['lastMonth'][$paymentCurrency])) {
                $counters['payments']['lastMonth']->put($paymentCurrency, collect());
                $counters['payments']['lastMonth'][$paymentCurrency]->total = 0;
                $counters['payments']['lastMonth'][$paymentCurrency]->count = 0;
            }
            $counters['payments']['lastMonth'][$paymentCurrency]->total += $payment->total_price;
            $counters['payments']['lastMonth'][$paymentCurrency]->count++;
        }
        $counters['payments']->total = Payment::query()->count();
 */
        $lastEgg = Egg::query()->latest('updated_at')->first();
        $syncLastUpdate = $lastEgg ? $lastEgg->updated_at->isoFormat('LLL') : __('unknown');


        //Get node information
        $nodes = collect();
        foreach ($DBnodes = Node::query()->get() as $DBnode) { //gets all node information and prepares the structure
            $nodeId = $DBnode['id'];
            $nodes->put($nodeId, collect());
            $nodes[$nodeId]->name = $DBnode['name'];
            $pteroNode = $pterodactylClient->getNode($nodeId);
            $nodes[$nodeId]->usagePercent = round(max($pteroNode['allocated_resources']['memory'] / ($pteroNode['memory'] * ($pteroNode['memory_overallocate'] + 100) / 100), $pteroNode['allocated_resources']['disk'] / ($pteroNode['disk'] * ($pteroNode['disk_overallocate'] + 100) / 100)) * 100, 2);
            $counters['totalUsagePercent'] += $nodes[$nodeId]->usagePercent;

            $nodes[$nodeId]->totalServers = 0;
            $nodes[$nodeId]->activeServers = 0;
            $nodes[$nodeId]->totalEarnings = 0;
            $nodes[$nodeId]->activeEarnings = 0;
        }
        $counters['totalUsagePercent'] = ($DBnodes->count()) ? round($counters['totalUsagePercent'] / $DBnodes->count(), 2) : 0;

        $response = ($pterodactylClient->getServers()->json());
        foreach ($response["data"] as $server) { //gets all servers from Pterodactyl and calculates total of credit usage for each node separately + total
            $nodeId = $server['attributes']['node'];

            if ($CPServer = Server::query()->where('pterodactyl_id', $server['attributes']['id'])->first()) {
                $prize = Server::query()->where('id', $CPServer->product_id)->first()->price;
                if (!$CPServer->suspended) {
                    $counters['earnings']->active += $prize;
                    $counters['servers']->active++;
                    $nodes[$nodeId]->activeEarnings += $prize;
                    $nodes[$nodeId]->activeServers++;
                }
                $counters['earnings']->total += $prize;
                $counters['servers']->total++;
                $nodes[$nodeId]->totalEarnings += $prize;
                $nodes[$nodeId]->totalServers++;
            }
        }


        return view('admin.overview.index', [
            'counters' => $counters,
            'nodes' => $nodes,
            'syncLastUpdate' => $syncLastUpdate,
            'perPageLimit' => ($counters['servers']->total != Server::query()->count()) ? true : false,
            'settings' => $settings
        ]);


    }
}
