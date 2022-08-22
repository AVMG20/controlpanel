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
use Illuminate\Support\Facades\Cache;

class OverviewController extends Controller
{
    public const TTL = 86400;

    public function index(PterodactylClient $pterodactylClient)
    {
        /** @var GeneralSettings $settings */
        $settings = app(GeneralSettings::class);



        $counters = Cache::remember('counters', self::TTL, function () {
            $output = collect();
            $output->put('users', User::query()->count());
            $output->put('credits', number_format(User::query()->sum('credits'), 2, '.', ''));
            $output->put('eggs', Egg::query()->count());
            $output->put('nests', Nest::query()->count());
            $output->put('locations', Location::query()->count());

            $output->put('servers', collect());
            $output['servers']->active = 0;
            $output['servers']->total = 0;
            $output->put('earnings', collect());
            $output['earnings']->active = 0;
            $output['earnings']->total = 0;
            $output->put('totalUsagePercent', 0);

            return $output;
        });

        $lastEgg = Egg::query()->latest('updated_at')->first();
        $syncLastUpdate = $lastEgg ? $lastEgg->updated_at->isoFormat('LLL') : __('unknown');
        $nodes = Cache::remember('nodes', self::TTL, function() use($syncLastUpdate, $pterodactylClient, $counters){
            $output = collect();
            foreach($nodes = Node::query()->get() as $node){ //gets all node information and prepares the structure
                $nodeId = $node['id'];
                $output->put($nodeId, collect());
                $output[$nodeId]->name = $node['name'];
                $node = $pterodactylClient->getNode($nodeId)->json()["attributes"];

                $output[$nodeId]->usagePercent = round(max($node['allocated_resources']['memory']/($node['memory']*($node['memory_overallocate']+100)/100), $node['allocated_resources']['disk']/($node['disk']*($node['disk_overallocate']+100)/100))*100, 2);
                $counters['totalUsagePercent'] += $output[$nodeId]->usagePercent;

                $output[$nodeId]->totalServers = 0;
                $output[$nodeId]->activeServers = 0;
                $output[$nodeId]->totalEarnings = 0;
                $output[$nodeId]->activeEarnings = 0;
            }
            $counters['totalUsagePercent'] = round($counters['totalUsagePercent']/$nodes->count(), 2);
            $response = ($pterodactylClient->getServers()->json());

            foreach($response["data"] as $server){ //gets all servers from Pterodactyl and calculates total of credit usage for each node separately + total
                $nodeId = $server['attributes']['node'];

                if($CPServer = Server::query()->where('pterodactyl_id', $server['attributes']['id'])->first()){
                    $price = $CPServer->price;
                    if (!$CPServer->suspended){
                        $counters['earnings']->active += $price;
                        $counters['servers']->active ++;
                        $output[$nodeId]->activeEarnings += $price;
                        $output[$nodeId]->activeServers ++;
                    }
                    $counters['earnings']->total += $price;
                    $counters['servers']->total ++;
                    $output[$nodeId]->totalEarnings += $price;
                    $output[$nodeId]->totalServers ++;
                }
            }
            return $output;
        });
            return view('admin.overview.index', [
                'counters'       => $counters,
                'nodes'          => $nodes,
                'syncLastUpdate' => $syncLastUpdate,
                'perPageLimit'   => ($counters['servers']->total != Server::query()->count())?true:false,
                'settings'       =>  $settings
            ]);


}
}
