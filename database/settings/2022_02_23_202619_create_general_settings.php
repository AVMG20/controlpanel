<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.credits_display_name', 'Credits');
        $this->migrator->add('general.main_site', '');

        $this->migrator->add('general.register_ip_check', false);

        $this->migrator->add('general.initial_user_credits', 0);
        $this->migrator->add('general.initial_server_limit', 0);
        $this->migrator->add('general.initial_user_role', 2);
    }
}
