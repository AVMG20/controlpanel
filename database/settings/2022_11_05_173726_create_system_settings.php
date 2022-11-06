<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateSystemSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system.register_ip_check', true);
    }
};
