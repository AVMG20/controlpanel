<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    //instead of showing Credits, show something like example 'Emeralds'
    public string $credits_display_name;

    //url to the main site
    public string $main_site;

    //check the ip during register for dupes
    public bool $register_ip_check;

    //the initial amount of credits given to the user on register
    public float $initial_user_credits;
    //the initial amount of credits given to the user on register
    public float $initial_server_limit;
    //the initial role given to the user on register
    public int $initial_user_role;

    //url location of the icon
    public string $panel_icon_url;
    //url location of the favicon
    public string $panel_favicon_url;

    public static function group(): string
    {
        return 'general';
    }
}
