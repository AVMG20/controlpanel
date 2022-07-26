<?php

namespace App\Console\Commands\Setup;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Settings\MailSettings;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use function __;

class SetupMailCommand extends Command
{
    protected PterodactylClient $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's:mail:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the email settings from the .env file';
    private MailSettings $settings;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PterodactylClient $client, MailSettings $settings)
    {
        parent::__construct();
        $this->client = $client;
        $this->settings = $settings;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // run the email setup
        $this->info('Setting up the email settings...');

        // get the email settings from the .env file
        $email_host = env('MAIL_HOST');
        $email_port = env('MAIL_PORT');
        $email_username = env('MAIL_USERNAME');
        $email_password = env('MAIL_PASSWORD');
        $email_encryption = env('MAIL_ENCRYPTION');
        $email_from_address = env('MAIL_FROM_ADDRESS');

        $this->settings->mail_host = $email_host;
        $this->settings->mail_port = $email_port;
        $this->settings->mail_username = $email_username;
        $this->settings->mail_password = $email_password;
        $this->settings->mail_encryption = $email_encryption;
        $this->settings->mail_from_address = $email_from_address;
        $this->settings->save();

    }
}
