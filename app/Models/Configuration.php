<?php

namespace App\Models;

use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Configuration extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Configuration $configuration) {
            $configuration->locations()->detach();
            $configuration->eggs()->detach();
        });
    }

    protected $fillable = [
        'name',
        'description',
        'price',
        'setup_price',
        'minimum_required_credits',
        'memory',
        'cpu',
        'swap',
        'disk',
        'io',
        'databases',
        'backups',
        'allocations',
        'disabled'
    ];

    protected $casts = [
        'price' => 'float',
        'setup_price' => 'float',
        'minimum_required_credits' => 'float',
        'memory' => 'int',
        'cpu' => 'int',
        'swap' => 'int',
        'disk' => 'int',
        'io' => 'int',
        'databases' => 'int',
        'backups' => 'int',
        'allocations' => 'int',
    ];

    protected function hourly_price()
    {
        return Attribute::get(function () {
            return ($this->price / 30) / 24;
        });
    }

//    protected function daily_price()
//    {
//        return ($this->price / 30);
//    }
//
//    protected function getWeeklyPrice()
//    {
//        return ($this->price / 4);
//    }

    /**
     * @return BelongsToMany
     */
    public function eggs(): BelongsToMany
    {
        return $this->belongsToMany(Egg::class);
    }

    /**
     * @return BelongsToMany
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }
}
