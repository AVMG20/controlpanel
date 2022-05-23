<?php

namespace App\Providers;

use App\Settings\MailSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        /** @var MailSettings $settings */
        $settings = $this->app->make(MailSettings::class);
        $settings->setConfig();
    }
}
