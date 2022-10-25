<?php

namespace App\Settings;


use Spatie\LaravelSettings\Settings;

class CustomizationSettings extends Settings
{


    public ?string $primary_color = "#F2F4F6";
    public ?string $secondary_color = "#FFFFFF";
    public ?string $tertiary_color = "#1F2937";
    public ?string $text_color = "#111827";
    public ?bool $logo_enabled = false;
    public ?bool $alert_enabled = true;
    public ?string $alert_message = NULL;
    public ?string $alert_type = NULL;

    public static function group(): string
    {
        return 'customization';
    }

    public function convert_hex_to_rgb($hex){
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return sprintf('%s, %s, %s', $r, $g, $b);
}

}
