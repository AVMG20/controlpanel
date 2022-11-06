<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class RemoveRegisterIpCheck extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->delete('general.register_ip_check');
    }
};