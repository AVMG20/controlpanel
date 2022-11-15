<?php

namespace App\Classes\Pterodactyl\Models;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Exception;

class PterodactylUser extends PterodactylClient
{
    /**
     * Check if there is already a user with the given email in Pterodactyl
     *
     * @param string $email
     * @return void
     * @throws PterodactylRequestException
     * @throws ValidationException
     */
    public function validatePterodactylUser(string $email)
    {
        $response = $this->getUserByEmail(trim($email));
        $data = $response->json();

        if (!empty($data['data'])) {
            #Feature, email the user, to verify he owns the email. so we can link the accounts instead of trowing an error
            throw ValidationException::withMessages([
                'pterodactyl_error' => __('User with given email already exists in pterodactyl!')
            ]);
        }
    }

    /**
     * Creates a new user in Pterodactyl
     *
     * @param Request $request
     * @param User $user
     * @return array
     * @throws ValidationException
     */
    public function createPterodactylUser(Request $request, User $user): array
    {
        try {
            $response = $this->createUser([
                "external_id" => App::environment('local') ? Str::random() : strval($user->id),
                "username" => strval($user->name),
                "email" => strval($user->email),
                "first_name" => strval($user->name),
                "last_name" => strval($user->name),
                "password" => $request->password,
                "root_admin" => false,
                "language" => config('app.locale')
            ]);
        } catch (Exception $exception) {
            $user->delete();
            logger('Creating user in pterodactyl failed', ['exception' => $exception]);

            throw ValidationException::withMessages([
                'pterodactyl_error' => __('Creating pterodactyl account failed! Please contact an administrator.')
            ]);
        }

        return $response->json();
    }

}
