<?php

namespace App\Models;

use App\Settings\PterodactylSettings;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Client\Response;

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
        'node',
        'allocation',
        'nest',
        'egg',
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
        'allocations' => 'int',
        'nest' => 'int',
        'egg' => 'int',
    ];

    protected $appends = [
        'pterodactyl_client_url',
        'pterodactyl_admin_url',
        'price_per_hour',
        'price_per_day',
    ];

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
            'node' => $data['attributes']['node'],
            'allocation' => $data['attributes']['allocation'],
            'nest' => $data['attributes']['nest'],
            'egg' => $data['attributes']['egg'],
            'price' => $price
        ]);
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

            if (!str_ends_with($settings->url, '/')) {
                $settings->url .= '/';
            }

            return $settings->url . 'server/' . $this->identifier;
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

            if (!str_ends_with($settings->url, '/')) {
                $settings->url .= '/';
            }

            return $settings->url . '/admin/servers/view/' . $this->pterodactyl_id;
        });
    }

    /**
     * Get the price per hour
     *
     * @return Attribute
     */
    public function pricePerHour(): Attribute
    {
        return Attribute::get(fn() => floatval(number_format($this->price / 30 / 24, 6)));
    }

    /**
     * Get the price per day
     *
     * @return Attribute
     */
    public function pricePerDay(): Attribute
    {
        return Attribute::get(fn() => floatval(number_format($this->price / 30, 6)));
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
}
