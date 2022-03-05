<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ServerSettings extends Settings
{
    //title displayed on the checkout page
    public string $checkout_title;
    //description displayed on the checkout page
    public string $checkout_description;

    //disable the input field if the value is being provided using query params
    public bool $disable_prefilled_location;
    public bool $disable_prefilled_configuration;
    public bool $disable_prefilled_egg;

    public static function group(): string
    {
        return 'server';
    }
}
