<?php

namespace App\Classes;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * ControlPanel Pterodactyl Api wrapper
 */
class Pterodactyl
{
    /**
     * option to pull more than the default 50 from pterodactyl
     */
    public const PER_PAGE = 200;

    /**
     * @return PendingRequest
     */
    public static function client(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . config("SETTINGS::SYSTEM:PTERODACTYL:TOKEN"),
            'Content-type' => 'application/json',
            'Accept' => 'Application/vnd.pterodactyl.v1+json',
        ])->baseUrl(config("SETTINGS::SYSTEM:PTERODACTYL:URL") . '/api');
    }

    public static function getLocations()
    {
    }

    public static function getEggs(int $nestId)
    {
    }

    public static function getNests()
    {
    }

    public static function getNodes()
    {
    }
}
