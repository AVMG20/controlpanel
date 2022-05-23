<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreatePterodactylSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pterodactyl.url', '');
        $this->migrator->add('pterodactyl.api_key', '');
    }
}
