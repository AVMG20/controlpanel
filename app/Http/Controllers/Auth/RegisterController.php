<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Pterodactyl\Models\PterodactylUser;
use App\Exceptions\PterodactylRequestException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Settings\CustomizationSettings;
use App\Settings\GeneralSettings;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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

    protected PterodactylUser $client;

    private CustomizationSettings $customizationSettings;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GeneralSettings $settings, CustomizationSettings $customizationSettings, PterodactylUser $client)
    {
        $this->middleware('guest');
        $this->settings = $settings;
        $this->client = $client;
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
            'username' => ['required', 'string', 'max:30', 'min:4', 'alpha_num', 'unique:users'],
            'first_name' => ['required', 'string', 'max:30', 'min:3'],
            'last_name' => ['string', 'max:30'],
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
        $this->client->validatePterodactylUser($request->email);

        //create user
        event(new Registered($user = $this->createUser($request->all())));

        //create pterodactyl user
        $data = $this->client->createPterodactylUser($request, $user);

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
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function createUser(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'credits' => $this->settings->initial_user_credits,
            'server_limit' => $this->settings->initial_server_limit,
            'password' => Hash::make($data['password']),
        ]);
    }

}
