<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $credits_display_name;

    public static function group(): string
    {
        return 'general';
    }
}
