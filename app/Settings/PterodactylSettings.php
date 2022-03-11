<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PterodactylSettings extends Settings
{
    //pterodactyl url
    public string $url;

    //pterodactyl api key
    public string $api_key;

    public static function group(): string
    {
        return 'pterodactyl';
    }

    /**
     * Get url with ensured ending backslash
     *
     * @return string
     */
    public function getUrl(): string
    {
        return str_ends_with($this->url, '/') ? $this->url : $this->url . '/';
    }
}
