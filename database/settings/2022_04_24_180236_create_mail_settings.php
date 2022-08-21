<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateMailSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mail.mail_host', 'mailhog');
        $this->migrator->add('mail.mail_port', 1025);
        $this->migrator->add('mail.mail_username', '');
        $this->migrator->add('mail.mail_password', '');
        $this->migrator->add('mail.mail_encryption', '');
        $this->migrator->add('mail.mail_from_address', 'hello@example.com');
        $this->migrator->add('mail.mail_from_name', str_replace(' ', '', config('app.name')));
        $this->migrator->add('mail.mail_enabled', false);
    }
}
