<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePterodactylSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pterodactyl.pterodactyl_url', '');
        $this->migrator->add('pterodactyl.pterodactyl_api_key', '');
    }
}
