<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GetLatestGithubVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cp:versioncheck:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the latest Version from Github';

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
     *
     * @return int
     */
    public function handle()
    {
        try{
            $latestVersion = Http::get('https://api.github.com/repos/AVMG20/controlpanel/tags')->json()[0]['name'];
            Storage::disk('local')->put('latestVersion', $latestVersion);
        } catch (Exception $e) {
            Storage::disk('local')->put('latestVersion', "unknown");
            Log::error($e);
        }

    }
}
