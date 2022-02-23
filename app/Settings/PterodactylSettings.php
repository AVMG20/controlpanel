<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PterodactylSettings extends Settings
{
    public string $pterodactyl_url;
    public string $pterodactyl_api_key;

    public static function group(): string
    {
        return 'pterodactyl';
    }
}
