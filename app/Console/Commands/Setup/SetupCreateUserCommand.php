<?php

namespace App\Console\Commands\Setup;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Models\User;
use App\Settings\GeneralSettings;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use function __;

class SetupCreateUserCommand extends Command
{
    protected PterodactylClient $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's:user:create {--ptero_id=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the initial admin user';
    private GeneralSettings $settings;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PterodactylClient $client, GeneralSettings $settings)
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
        $ptero_id = $this->option('ptero_id') ?? $this->ask('Please specify your Pterodactyl ID.');
        $password = $this->option('password') ?? $this->secret('Please specify your password.');

        // Validate user input
        $validator = Validator::make([
            'ptero_id' => $ptero_id,
            'password' => $password,
        ], [
            'ptero_id' => 'required|numeric|integer|min:1|max:2147483647',
            'password' => 'required|string|min:8|max:60',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return 0;
        }

        // Check if the user exists in the database
        if(User::where('pterodactyl_id', $ptero_id)->exists()) {
            if($this->confirm('This user already exists in the database. Execute this command again? This will delete the user.')) {
                $this->error('Deleting this user...');
                User::where('pterodactyl_id', $ptero_id)->delete();

                return $this->handle();
            } else {
                $this->error('User already exists in the database. Aborting.');
                return 0;
            }

        }

        //TODO: Do something with response (check for status code and give hints based upon that)
        $response = $this->client->getUser($ptero_id);
        $data = $response->json();

        if (!isset($data['attributes'])) {
            $this->InvalidDataReturnedByPtero($response);
        }

        $data = $data['attributes'];

        //check if Pterodactyl has given us some errors
        if (isset($data['errors'])) {
            if (isset($data['errors'][0]['code'])) $this->error("code: {$data['errors'][0]['code']}");
            if (isset($data['errors'][0]['status'])) $this->error("status: {$data['errors'][0]['status']}");
            if (isset($data['errors'][0]['detail'])) $this->error("detail: {$data['errors'][0]['detail']}");
            return 0;
        }

        //check if data returned is not empty
        if (!isset($data['first_name']) || !isset($data['email']) || !isset($data['id'])){
            $this->InvalidDataReturnedByPtero($response);
        }

        /** @var User $user */
        $user = User::create([
            'name' => $data['first_name'],
            'email' => $data['email'],
            'credits' => $this->settings->initial_user_credits,
            'server_limit' => $this->settings->initial_server_limit,
            'password' => Hash::make($password),
            'pterodactyl_id' => $data['id']
        ]);

        try {
            $role = Role::findById(1);
            $user->assignRole($role);
        } catch (Exception $exception){
            $this->info('Unable to link user with role with id 1');
        }

        $this->table(['Field', 'Value'], [
            ['ID', $user->id],
            ['Ptero-ID', $user->pterodactyl_id],
            ['Username', $user->name],
            ['Email', $user->email],
            ['credits', $user->credits],
            ['server_limit', $user->server_limit],
        ]);

        $this->info(__('Success! You can now login using the shown email and the provided password.'));

        return 1;
    }

    /**
     * @param PromiseInterface|Response $response
     * @return void
     */
    public function InvalidDataReturnedByPtero(PromiseInterface|Response $response): void
    {
        $this->error(__('The data returned from Pterodactyl is empty or incorrect!'));
        $this->error(__('Data returned from Pterodactyl: :data', ['data' => json_encode($response->json())]));
    }
}
