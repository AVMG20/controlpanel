<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateCustomizationSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('customization.primary_color', '#F2F4F6');
        $this->migrator->add('customization.secondary_color', '#FFFFFF');
        $this->migrator->add('customization.tertiary_color', '#1F2937');
        $this->migrator->add('customization.hover_color', '#1F2937');
    }
}
