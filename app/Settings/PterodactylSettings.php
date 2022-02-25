<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PterodactylSettings extends Settings
{
    public string $url;
    public string $api_key;

    public static function group(): string
    {
        return 'pterodactyl';
    }
}
