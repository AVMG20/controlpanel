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

    public function index(PterodactylClient $pterodactylClient, GeneralSettings $settings)
    {
        $userCount =  User::query()->count();
        $creditCount = User::query()->sum('credits');
        $creditCount =  number_format($creditCount, 2, '.', '');

        //Get counters
        $counters = collect();
        //Set basic variables in the collection
        $counters->put('users', $userCount);
        $counters->put('credits', $creditCount);
       // $counters->put('payments', Payment::all()->count());
        $counters->put('eggs', Egg::all()->count());
        $counters->put('nests', Nest::all()->count());
        $counters->put('locations', Location::all()->count());

        //Prepare for counting
        $counters->put('servers', collect());
        $counters['servers']->active = 0;
        $counters['servers']->total = 0;
        $counters->put('earnings', collect());
        $counters['earnings']->active = 0;
        $counters['earnings']->total = 0;
        $counters->put('totalUsagePercent', 0);

        //ToDo add payments from v0.8 PR

        $lastEgg = Egg::query()->latest('updated_at')->first();
        $syncLastUpdate = $lastEgg ? $lastEgg->updated_at->isoFormat('LLL') : __('unknown');


        //Get node information
        $nodes = collect();
        foreach ($DBnodes = Node::all() as $DBnode) { //gets all node information and prepares the structure
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

            $serverId = $server['attributes']['id'];
            $nodeId = $server['attributes']['node'];

            $CPServer = Server::query()->where('pterodactyl_id', $serverId)->first();

            if ($CPServer) {
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
            'lastPteroSync' => $syncLastUpdate,
            'settings' => $settings
        ]);


    }
}
