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
use App\Settings\PterodactylSettings;

class OverviewController extends Controller
{
    public const TTL = 86400;

    public function index(GeneralSettings $settings)
    {

        //Check the last Sync Status
        $lastEgg = Egg::query()->latest('updated_at')->first();
        $syncLastUpdate = $lastEgg ? $lastEgg->updated_at->isoFormat('LLL') : __('unknown');

        //Generate the Counters used on the Overview
        $counters = $this->constructNodeInfo()["counters"];
        $nodes = $this->constructNodeInfo()["nodes"];

        return view('admin.overview.index', [
            'counters' => $counters,
            'nodeCounters' => $nodes,
            'lastPteroSync' => $syncLastUpdate,
            'settings' => $settings
        ]);


    }


    /**
     * @return \Illuminate\Support\Collection
     */
    private function constructCounters()
    {

        $userCount = User::query()->count();
        $creditCount = User::query()->sum('credits');
        $creditCount = number_format($creditCount, 2, '.', '');
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

        return $counters;
    }


    /**
     * @return array
     */
    private function constructNodeInfo()
    {
        $pterosettings = new PterodactylSettings();
        $pterodactylClient = new PterodactylClient($pterosettings);
        $counters = $this->constructCounters();

        //Get node information
        $nodes = collect();
        foreach ($DBnodes = Node::all() as $DBnode) { //gets all node information and prepares the structure
            $nodeId = $DBnode['id'];
            $nodes->put($nodeId, collect());
            $nodes[$nodeId]->id = $nodeId;
            $nodes[$nodeId]->name = $DBnode['name'];
            $pteroNode = $pterodactylClient->getNode($nodeId)->json()["attributes"];
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
                $price = Server::query()->where('id', $CPServer->product_id)->first()->price;
                if (!$CPServer->suspended) {
                    $counters['earnings']->active += $price;
                    $counters['servers']->active++;
                    $nodes[$nodeId]->activeEarnings += $price;
                    $nodes[$nodeId]->activeServers++;
                }
                $counters['earnings']->total += $price;
                $counters['servers']->total++;
                $nodes[$nodeId]->totalEarnings += $price;
                $nodes[$nodeId]->totalServers++;
            }
        }
        return [
            'counters' => $counters,
            'nodes' => $nodes
        ];
    }


}
