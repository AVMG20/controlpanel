<?php

namespace App\Console\Commands\Setup;

use App\Settings\PterodactylSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SetupLinkPteroCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's:pterodactyl:link {--url=} {--key=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the connection to pterodactyl';
    private PterodactylSettings $pterodactylSettings;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PterodactylSettings $pterodactylSettings)
    {
        parent::__construct();
        $this->pterodactylSettings = $pterodactylSettings;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ptero_url = $this->option('url') ?? $this->ask(__('Please specify the url to your pterodactyl panel') , 'https://panel.controlpanel.gg/');
        $ptero_key = $this->option('key') ?? $this->ask(__('Please provide the API key you created in your Pterodactyl panel'));

        // Validate user input
        $validator = Validator::make(compact('ptero_url', 'ptero_key'), [
            'ptero_url' => 'required|url',
            'ptero_key' => 'required|string|min:8|max:60',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return 0;
        }

        //save settings
        $this->pterodactylSettings->url = $ptero_url;
        $this->pterodactylSettings->api_key = $ptero_key;

        $this->pterodactylSettings->save();
        //TODO validate Pterodactyl API connection

        $this->info('Pterodactyl credentials saved successfully!');
        return 0;
    }
}
