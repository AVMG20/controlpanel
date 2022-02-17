<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user command';

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
        $name = $this->ask(__('What is your username'));
        $email = $this->ask(__('What email would you like to use?'));
        $password = $this->secret(__('What password would you like to use?'));
        $password = Hash::make($password);

        //create user
        /** @var User $user */
        $user = User::query()->create(compact('name', 'email' , 'password'));

        //give user admin role
        try {
            $adminRole = Role::findByName('Admin');
            $user->assignRole($adminRole);
        } catch (RoleDoesNotExist $exception){
            $this->error(__("Unable to find role with name ':name'", ['name' => 'Admin']));
        }

        //done
        $this->line('User created successfully!');
        return 0;
    }
}
