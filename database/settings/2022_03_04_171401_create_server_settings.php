<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CheckoutSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('checkout.main_site', '');
    }
}
