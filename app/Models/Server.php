<?php

namespace App\Models;

use App\Classes\Pterodactyl\PterodactylClient;
use App\Exceptions\PterodactylRequestException;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Node;
use App\Settings\PterodactylSettings;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'pterodactyl_id',
        'user_id',
        'identifier',
        'name',
        'description',
        'status',
        'suspended',
        'memory',
        'cpu',
        'swap',
        'disk',
        'io',
        'databases',
        'backups',
        'allocations',
        'threads',
        'oom_disabled',
        'node_id',
        'allocation_id',
        'nest_id',
        'egg_id',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pterodactyl_id' => 'int',
        'suspended' => 'bool',
        'price' => 'float',
        'memory' => 'int',
        'cpu' => 'int',
        'swap' => 'int',
        'disk' => 'int',
        'io' => 'int',
        'databases' => 'int',
        'backups' => 'int',
        'allocations_id' => 'int',
        'nest_id' => 'int',
        'egg_id' => 'int',
        'node_id' => 'int',
    ];

    protected $appends = [
        'pterodactyl_client_url',
        'pterodactyl_admin_url',
        'price_per_hour',
        'price_per_day',
    ];

    protected static function boot()
    {
        parent::boot();

        //delete server on pterodactyl
        static::deleting(function (Server $server) {
            /** @var PterodactylClient $client */
            $client = app(PterodactylClient::class);

            /** @var PterodactylSettings $settings */
            $settings = app(PterodactylSettings::class);

            try {
                //delete server on pterodactyl
                $client->deleteServer($server->pterodactyl_id);
            } catch (PterodactylRequestException $exception) {
                //throw exception if it's not a 404 error
                if ($exception->getCode() !== 404) throw $exception;
            }
        });
    }

    /**
     * Create server object using Pterodactyl response
     *
     * @param Response $response
     * @param User $user
     * @param float $price
     * @return Server
     */
    public static function createFromPterodactylResponse(Response $response, User $user, float $price): Server
    {
        $data = $response->body();
        $data = json_decode($data, true);

        return self::create([
            'pterodactyl_id' => $data['attributes']['id'],
            'user_id' => $user->id,
            'identifier' => $data['attributes']['identifier'],
            'name' => $data['attributes']['name'],
            'description' => $data['attributes']['description'],
            'status' => $data['attributes']['status'],
            'suspended' => $data['attributes']['suspended'],
            'memory' => $data['attributes']['limits']['memory'],
            'cpu' => $data['attributes']['limits']['cpu'],
            'swap' => $data['attributes']['limits']['swap'],
            'disk' => $data['attributes']['limits']['disk'],
            'io' => $data['attributes']['limits']['io'],
            'threads' => $data['attributes']['limits']['threads'],
            'oom_disabled' => $data['attributes']['limits']['oom_disabled'],
            'databases' => $data['attributes']['feature_limits']['databases'],
            'backups' => $data['attributes']['feature_limits']['backups'],
            'allocations' => $data['attributes']['feature_limits']['allocations'],
            'node_id' => $data['attributes']['node'],
            'allocation_id' => $data['attributes']['allocation'],
            'nest_id' => $data['attributes']['nest'],
            'egg_id' => $data['attributes']['egg'],
            'price' => $price
        ]);
    }

    /**
     * Sync the ressources of a specifiy pterodactyl server to CPGG
     * @return void
     **/
    public function syncPterodactylServerSpecs()
    {
        $settings = app(PterodactylSettings::class);
        $client = new PterodactylClient($settings);

        try {
            $data = $client->getServer($this->pterodactyl_id)->body();
            $data = json_decode($data, true);

            $this->pterodactyl_id = $data['attributes']['id'];
            $this->identifier = $data['attributes']['identifier'];
            $this->name = $data['attributes']['name'];
            $this->description = $data['attributes']['description'];
            $this->status = $data['attributes']['status'];
            $this->suspended = $data['attributes']['suspended'];
            $this->memory = $data['attributes']['limits']['memory'];
            $this->cpu = $data['attributes']['limits']['cpu'];
            $this->swap = $data['attributes']['limits']['swap'];
            $this->disk = $data['attributes']['limits']['disk'];
            $this->io = $data['attributes']['limits']['io'];
            $this->threads = $data['attributes']['limits']['threads'];
            $this->oom_disabled = $data['attributes']['limits']['oom_disabled'];
            $this->databases = $data['attributes']['feature_limits']['databases'];
            $this->backups = $data['attributes']['feature_limits']['backups'];
            $this->allocations = $data['attributes']['feature_limits']['allocations'];
            $this->node_id = $data['attributes']['node'];
            $this->allocation_id = $data['attributes']['allocation'];
            $this->nest_id = $data['attributes']['nest'];
            $this->egg_id = $data['attributes']['egg'];
            $this->save();

        } catch (PterodactylRequestException $exception) {
            //delete server if it's  a 404 error
            if ($exception->getCode() == 404) $this->delete();
            Log::Error("There was an error when trying to Sync Server " . $this->name . "(" . $this->identifier . ") : " . $exception);
        }
    }


    /* Charge the user for the server
    *
    * @return bool true if the user was charged, false if the user doesn't have enough money
    */
    public function chargeCredits(): bool
    {
        $hourlyPrice = $this->price / 30 / 24;

        /** @var User $user */
        $user = $this->user;

        static $suspendedUsers = [];

        //keep track of users that had there servers suspended, no need to charge other servers
        if (in_array($user->id, $suspendedUsers)) return false;

        //check if the user has enough credits to pay for the server
        if ($user->credits >= $hourlyPrice) {
            $user->decrement('credits', $hourlyPrice);
            return true;
        }

        //suspend user servers
        $user->suspendAllServers();
        $suspendedUsers[] = $user->id;

        return false;
    }

    /**
     * Suspends the server
     *
     * @return Server
     */
    public function suspend(): static
    {
        $this->suspended = true;
        $this->save();

        return $this;
    }

    /**
     * Unsuspends the server
     *
     * @return Server
     */
    public function unsuspend(): static
    {
        $this->suspended = false;
        $this->save();

        return $this;
    }

    /**
     * Generate the client url to Pterodactyl
     *
     * @return Attribute
     */
    public function pterodactylClientUrl(): Attribute
    {
        return Attribute::get(function () {
            /** @var PterodactylSettings $settings */
            $settings = app(PterodactylSettings::class);

            return $settings->getUrl() . 'server/' . $this->identifier;
        });
    }

    /**
     * Generate the admin url to Pterodactyl
     *
     * @return Attribute
     */
    public function pterodactylAdminUrl(): Attribute
    {
        return Attribute::get(function () {
            /** @var PterodactylSettings $settings */
            $settings = app(PterodactylSettings::class);

            return $settings->getUrl() . '/admin/servers/view/' . $this->pterodactyl_id;
        });
    }

    /**
     * Get the price per hour
     *
     * @return Attribute
     */
    public function pricePerHour(): Attribute
    {
        return Attribute::get(fn() => number_format($this->price / 30 / 24, 2, '.', ''));
    }

    /**
     * Get the price per day
     *
     * @return Attribute
     */
    public function pricePerDay(): Attribute
    {
        return Attribute::get(fn() => number_format($this->price / 30, 2, '.', ''));
    }

    /**
     * Get user object
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get egg object
     *
     * @return BelongsTo
     */
    public function egg(): BelongsTo
    {
        return $this->belongsTo(Egg::class);
    }

    /**
     * Get node object
     *
     * @return BelongsTo
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }
}
