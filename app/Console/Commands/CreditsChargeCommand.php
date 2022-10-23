<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class CreditsChargeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credits:charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge all servers for their credits usage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Server::query()->where('suspended' , '=' , '0')->chunk(100 , function ($servers) {
            /** @var Server $server */
            foreach ($servers as $server) {

                $server->chargeCredits();
            }
        });
    }
}
