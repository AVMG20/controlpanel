<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateServerSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('checkout.checkout_title', 'Configure');
        $this->migrator->add('checkout.checkout_description', 'Configure your desired options and continue to checkout.');

        $this->migrator->add('checkout.disable_prefilled_egg', true);
        $this->migrator->add('checkout.disable_prefilled_location', true);
        $this->migrator->add('checkout.disable_prefilled_configuration', true);
    }
}
