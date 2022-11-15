<?php

namespace App\Settings;


use Spatie\LaravelSettings\Settings;

class CustomizationSettings extends Settings
{

    public ?string $primary_color = "#F2F4F6";
    public ?string $secondary_color = "#FFFFFF";
    public ?string $tertiary_color = "#1F2937";
    public ?string $text_color = "#111827";

    public ?bool $alert_enabled = true;
    public ?string $alert_message = NULL;
    public ?string $alert_type = NULL;

    public ?string $custom_js_filename = NULL;

    public ?bool $logo_enabled = false;
    public ?string $custom_logo_filename = NULL;
    public ?string $custom_favicon_filename = NULL;
    public ?string $custom_icon_filename = NULL;



    public static function group(): string
    {
        return 'customization';
    }

    /**
     * @param $hex
     * @return string|void
     */
    public function convert_hex_to_rgb($hex)
    {
        if ($hex == null) return;

        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return sprintf('%s, %s, %s', $r, $g, $b);
    }

}
