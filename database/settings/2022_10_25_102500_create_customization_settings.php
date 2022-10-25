<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateCustomizationSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('customization.primary_color', '#F2F4F6');
        $this->migrator->add('customization.secondary_color', '#FFFFFF');
        $this->migrator->add('customization.tertiary_color', '#1F2937');
        $this->migrator->add('customization.text_color', '#111827');

        $this->migrator->add('customization.logo_enabled', false);

        $this->migrator->add('customization.alert_enabled', true);
        $this->migrator->add('customization.alert_message', "Welcome to our Dashboard. You can edit this Message in your Settings!");
        $this->migrator->add('customization.alert_type', "dark");
    }
}
