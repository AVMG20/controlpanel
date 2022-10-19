<?php

namespace App\Console\Commands\Sync;

use App\Models\Server;
use Exception;
use Illuminate\Console\Command;

class SyncPteroServersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:pterodactyl:servers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pterodactyl Servers to controlpanel';

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
        $this->newLine();
        $this->line('Syncing Servers..this might take a while.');

        Server::inRandomOrder()->chunk(15, function($servers) {
            /** @var Server $server */
            foreach ($servers as $server) {
                Server::syncPterodactylServerSpecs($server);
            }
        });
        $this->newLine();
        $this->line('Pterodactyl servers synced successfully!');
        return 0;
    }
}
