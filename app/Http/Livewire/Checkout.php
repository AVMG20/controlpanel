<?php

namespace App\Http\Livewire;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Exceptions\PterodactylRequestException;
use App\Helper\PterodactylServerHelper;
use App\Models\Configuration;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Models\Pterodactyl\Nest;
use App\Models\Server;
use App\Models\User;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Checkout extends Component
{
    public string $credits_display_name;

    public array $nests = [];
    public array $eggs = [];
    public array $locations = [];
    public array $configurations = [];

    public ?int $selected_egg_id = -1;
    public ?int $selected_location_id = -1;
    public ?int $selected_configuration_id = -1;

    public ?array $selected_egg = [];
    public ?array $selected_location = [];
    public ?array $selected_configuration = [];

    public ?string $name = '';

    protected $rules = [
        'name' => ['required', 'string', 'max:60'],
        'selected_egg_id' => ['required', 'integer', 'exists:eggs,id'],
        'selected_location_id' => ['required', 'integer', 'exists:locations,id'],
        'selected_configuration_id' => ['required', 'integer', 'exists:configurations,id'],
    ];

    public function mount()
    {
        $generalSettings = app(GeneralSettings::class);
        $this->credits_display_name = $generalSettings->credits_display_name;
    }

    public function render()
    {
        $this->getNests();
        $this->getLocations();
        $this->getConfigurations();

        $this->setSelectedModels();

        return view('livewire.checkout');
    }

    public function updated($name)
    {
        $this->validateOnly($name);
    }

    /**
     * Create server
     *
     * @param PterodactylServerHelper $pterodactylServerHelper
     * @param PterodactylClient $client
     * @return RedirectResponse
     */
    public function createServer(PterodactylServerHelper $pterodactylServerHelper, PterodactylClient $client)
    {
        /** @var User $user */
        /** @var Egg $egg */
        /** @var Location $location */
        /** @var Configuration $configuration */

        if (!auth()->check()) { //TODO: implement register and login into form
            return redirect()->back()->with('error', __('You need to login before you can create a server.'));
        }

        //validate request
        $this->validate();

        //fetch needed models
        $user = auth()->user();
        $egg = Egg::query()->where('id', '=', $this->selected_egg_id)->firstOrFail();
        $location = Location::query()->where('id', '=', $this->selected_location_id)->firstOrFail();
        $configuration = Configuration::query()->where('id', '=', $this->selected_configuration_id)->firstOrFail();

        //validate price
        $redirect = $this->validatePrice($user, $configuration);
        if ($redirect) return $redirect;

        //create server
        $data = $pterodactylServerHelper->createServerData(
            $this->name,
            auth()->user(),
            $egg,
            $location,
            $configuration,
        );

        try {
            $response = $client->createServer($data);
            Server::createFromPterodactylResponse($response, $user, $configuration->price);
        } catch (PterodactylRequestException $exception) {
            logger('Creating server failed', ['exception' => $exception]);
            return redirect()->back()->with('error', $exception->getPterodactylErrorMessage());
        } catch (Exception $exception) {
            logger('Creating server failed', ['exception' => $exception]);
            return redirect()->back()->with('error', __('Unexpected error during server creation'));
        }

        return redirect()->route('dashboard.index')->with('success', __('Server created successfully!'));
    }

    /**
     * Get all available nests and eggs with configurations
     */
    public function getNests()
    {
        $this->nests = Nest::query()
            ->with('eggs', function (HasMany $builder) {
                $builder->whereHas('configurations', function (Builder $builder) {
                    $builder->where('disabled', '=', false);
                });
            })
            ->whereHas('eggs', function (Builder $builder) {
                $builder->whereHas('configurations', function (Builder $builder) {
                    $builder->where('disabled', '=', false);
                });
            })
            ->get()
            ->toArray();

        $this->eggs = [];

        array_filter(array_map(function ($nest) {
            $this->eggs = array_merge($this->eggs, $nest['eggs']);
        }, $this->nests));
    }

    /**
     * Get all available locations (locations with a configuration that has eggs)
     */
    public function getLocations(): void
    {
        if (!$this->selected_egg_id) return;

        $this->locations = Location::query()->whereHas('configurations', function (Builder $builder) {
            $builder->whereHas('eggs', function (Builder $builder) {
                $builder->where('id', '=', $this->selected_egg_id);
            })->where('disabled', '=', false);
        })->get()->toArray();

        if (count($this->locations) === 1) {
            $this->selected_location_id = $this->locations[0]['id'];
        }
    }

    /**
     * Get all configurations using the selected location
     *
     * @return Void
     */
    public function getConfigurations(): void
    {
        if (!$this->selected_egg_id) return;
        if (!$this->selected_location_id) return;

        $this->configurations = Configuration::query()
            ->whereHas('locations', function (Builder $builder) {
                $builder->where('id', '=', $this->selected_location_id);
            })
            ->whereHas('eggs', function (Builder $builder) {
                $builder->where('id', '=', $this->selected_egg_id);
            })
            ->orderBy('price')
            ->get()
            ->toArray();

        if (count($this->configurations) === 1) {
            $this->selected_configuration_id = $this->configurations[0]['id'];
        }
    }

    /**
     * Set the selected models based on the selected_ids
     *
     * @return void
     */
    public function setSelectedModels(): void
    {
        //set selected egg
        if (array_search($this->selected_egg_id, array_column($this->eggs, 'id')) !== false)
            $this->selected_egg = $this->eggs[array_search($this->selected_egg_id, array_column($this->eggs, 'id'))] ?? null;

        //set selected location
        if (array_search($this->selected_location_id, array_column($this->locations, 'id')) !== false)
            $this->selected_location = $this->locations[array_search($this->selected_location_id, array_column($this->locations, 'id'))] ?? null;

        //set selected configuration
        if (array_search($this->selected_configuration_id, array_column($this->configurations, 'id')) !== false)
            $this->selected_configuration = $this->configurations[array_search($this->selected_configuration_id, array_column($this->configurations, 'id'))] ?? null;
    }

    /**
     * Validate if the user has enough credits to order the selected configuration
     *
     * @param User $user
     * @param Configuration $configuration
     * @return RedirectResponse|void
     */
    protected function validatePrice(User $user, Configuration $configuration)
    {
        //check if the user has enough credits
        if ($user->credits < $configuration->minimum_required_credits) {
            return redirect()->back()->with('error', __('You do not have the minimum required :credits to order this configuration. The Minimum needed for this configuration is ' . $configuration->minimum_required_credits,
                ['credits' => $this->credits_display_name]));
        }

        //check if the user has enough credits
        if ($user->credits < ($configuration->setup_price + $configuration->hourly_price)) {
            return redirect()->back()->with('error', __('You do not have enough :credits to order this configuration',
                ['credits' => $this->credits_display_name]));
        }

        //check server limit
        if ($user->servers()->count() >= $user->server_limit) {
            return redirect()->back()->with('error', __('You have reached your server limit'));
        }
    }
}
