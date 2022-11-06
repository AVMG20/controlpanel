<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public bool $register_ip_check;

    public bool $creation_of_new_users;

    public bool $creation_of_new_servers;

    public static function group(): string
    {
        return "system";
    }
}