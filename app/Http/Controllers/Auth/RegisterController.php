<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Exceptions\PterodactylRequestException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Settings\CustomizationSettings;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected GeneralSettings $settings;

    protected PterodactylClient $client;
    private CustomizationSettings $customizationSettings;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GeneralSettings $settings, CustomizationSettings $customizationSettings, PterodactylClient $client)
    {
        $this->middleware('guest');
        $this->settings = $settings;
        $this->client = $client;
        $this->customizationSettings = $customizationSettings;
    }

    public function showRegistrationForm()
    {
        return view('auth.register', ['customizationSettings' => $this->customizationSettings]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:30', 'min:4', 'alpha_num', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:64', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule()]
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     * @throws ValidationException
     * @throws PterodactylRequestException
     */
    public function register(Request $request)
    {
        //normal validation
        $this->validator($request->all())->validate();

        //check if user already exists in ptero
        $this->validatePterodactylUser($request->email);

        //create user
        event(new Registered($user = $this->createUser($request->all())));

        //create pterodactyl user
        $data = $this->createPterodactylUser($request, $user);

        //update user with pterodactyl_id
        $user->update([
            'pterodactyl_id' => $data['attributes']['id']
        ]);

        //link user to initial role
        $user->assignRole($this->settings->initial_user_role);

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }


    /**
     * Check if there is already a user with the given email in Pterodactyl
     *
     * @param string $email
     * @return void
     * @throws PterodactylRequestException
     * @throws ValidationException
     */
    protected function validatePterodactylUser(string $email)
    {
        $response = $this->client->getUserByEmail(trim($email));
        $data = $response->json();

        if (!empty($data['data'])) {
            #Feature, email the user, to verify he owns the email. so we can link the accounts instead of trowing an error
            throw ValidationException::withMessages([
                'pterodactyl_error' => __('User with given email already exists in pterodactyl!')
            ]);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return array
     * @throws ValidationException
     */
    protected function createPterodactylUser(Request $request, User $user): array
    {
        try {
            $response = $this->client->createUser([
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

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'credits' => $this->settings->initial_user_credits,
            'server_limit' => $this->settings->initial_server_limit,
            'password' => Hash::make($data['password']),
        ]);
    }

}
