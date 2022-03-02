<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.credits_display_name', 'Credits');
        $this->migrator->add('general.main_site', '');
    }
}