<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateCustomizationSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('customization.primary_color', '');
        $this->migrator->add('customization.secondary_color', '');
        $this->migrator->add('customization.tertiary_color', '');
        $this->migrator->add('customization.text_color', '');

        $this->migrator->add('customization.logo_enabled', false);

        $this->migrator->add('customization.alert_enabled', false);
        $this->migrator->add('customization.alert_message', '');
        $this->migrator->add('customization.alert_type', "dark");

        $this->migrator->add('customization.custom_js_filename', '');
        $this->migrator->add('customization.custom_icon_filename', '');
        $this->migrator->add('customization.custom_favicon_filename', '');
        $this->migrator->add('customization.custom_logo_filename', '');
    }
}
