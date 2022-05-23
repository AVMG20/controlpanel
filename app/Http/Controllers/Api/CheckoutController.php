<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Models\Pterodactyl\Nest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Public endpoints for used by the checkout page
 * You can ofc also use this for your own custom applications
 */
class CheckoutController extends Controller
{
    /**
     * Get all available locations (locations with a configuration that has eggs)
     *
     * @param Egg $egg
     * @return Collection
     */
    public function locations(Egg $egg): Collection
    {
        return Location::query()->whereHas('configurations', function (Builder $builder) use ($egg) {
            $builder->whereHas('eggs', function (Builder $builder) use ($egg) {
                $builder->where('id', '=', $egg->id);
            })->where('disabled', '=', false);
        })->get();
    }

    /**
     * Get all configurations using the selected location
     *
     * @param Egg $egg
     * @param Location $location
     * @return Collection
     */
    public function configurations(Egg $egg, Location $location): Collection
    {
        return Configuration::query()
            ->whereHas('locations', function (Builder $builder) use ($location) {
                $builder->where('id', '=', $location->id);
            })
            ->whereHas('eggs', function (Builder $builder) use ($egg) {
                $builder->where('id', '=', $egg->id);
            })
            ->orderBy('price')
            ->get();
    }

    /**
     * Get all available eggs from the selected configuration
     *
     * @param Configuration $configuration
     * @return Collection
     */
    public function eggs(Configuration $configuration): Collection
    {
        return $configuration->eggs()->get();
    }

    /**
     * Get all available nests and eggs with configurations
     *
     * @return Collection
     */
    public function nests(): Collection
    {
        return Nest::query()
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
            ->get();
    }


    public function createServer()
    {

    }
}
