<?php

namespace App\Console\Commands\Setup;

use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Models\Pterodactyl\Nest;
use App\Models\Pterodactyl\Node;
use App\Models\Server;
use Exception;
use Illuminate\Console\Command;

class SetupSyncPteroCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's:pterodactyl:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pterodactyl records to controlpanel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @note The order of syncing is important!
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(5);
        $bar->start();

        Location::syncLocations();
        $bar->advance();

        Node::syncNodes();
        $bar->advance();

        Nest::syncNests();
        $bar->advance();

        Egg::syncEggs();
        $bar->advance();

        $this->newLine();
        $this->line('Syncing Servers..this might take a while.');

        Server::inRandomOrder()->chunk(15, function($servers) {
            foreach ($servers as $server) {
                Server::syncPterodactylServerSpecs($server);
            }
        });
        $bar->advance();

        $this->newLine();
        $this->line('Pterodactyl synced successfully!');
        return 0;
    }
}
