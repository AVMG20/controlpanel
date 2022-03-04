<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ServerSettings extends Settings
{
    public bool $disable_prefilled_location;
    public bool $disable_prefilled_configuration;
    public bool $disable_prefilled_egg;

    //disable the server creation page if there are no query params given
    //example http://controlpanel.localhost/checkout?location=2&configuration=17&egg=13
    public bool $disable_server_creation_without_params;

    public static function group(): string
    {
        return 'server';
    }
}
