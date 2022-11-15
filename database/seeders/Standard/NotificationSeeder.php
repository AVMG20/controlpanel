<?php

namespace Database\Seeders\Standard;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationTemplate::query()->firstOrCreate([
            'name' => 'welcome-message'
        ],[
            'name' => 'welcome-message',
            'title' => "Getting started",
            'content' => "
               <p>Hello <strong>{{\$user->username}}</strong>, Welcome to our dashboard!</p>
                <h4>Information</h4>
                <p>This dashboard can be used to create and delete servers.<br /> These servers can be used and managed on our pterodactyl panel.<br /> If you have any questions, please join our Discord server and #create-a-ticket.</p>
                <p>We hope you can enjoy this hosting experience and if you have any suggestions please let us know!</p>
                <p>Regards,<br />Controlpanel</p>",
            'channels' => json_encode(['mail'])
        ]);

        NotificationTemplate::query()->firstOrCreate([
            'name' => 'test-notification'
        ],[
            'name' => 'test-notification',
            'title' => "Testing",
            'content' => "
               <p>Hello <strong>{{\$user->username}}</strong>, Your Mail settings are configured correctly!</p>
               <p>Regards,<br />Controlpanel</p>",
            'channels' => json_encode(['mail'])
        ]);

        NotificationTemplate::query()->firstOrCreate([
            'name' => 'servers-suspended'
        ],[
            'name' => 'servers-suspended',
            'title' => "Your servers have been suspended",
            'content' => "
               <p>
               Hello <strong>{{\$user->username}}</strong>, Your servers have been suspended! <br>
               Please recharge your account to continue using our services.
               </p>
               <p>Regards,<br />Controlpanel</p>",
            'channels' => json_encode(['mail'])
        ]);

        NotificationTemplate::query()->firstOrCreate([
            'name' => 'servers-unsuspended'
        ],[
            'name' => 'servers-unsuspended',
            'title' => "Your servers have been unsuspended",
            'content' => "
               <p>
               Hello <strong>{{\$user->username}}</strong>, Your servers have been unsuspended! <br>
               You can now use your servers again.
               </p>
               <p>Regards,<br />Controlpanel</p>",
            'channels' => json_encode(['mail'])
        ]);
    }
}
