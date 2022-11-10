<?php

namespace App\Providers;

use App\Settings\MailSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
        $this->getBranchName();


    }

    /**
     * @return void
     */
    public function getBranchName(): void
    {
        //get the current Branch name and latest git Version the Instance is running on and safe it as a config variable
        try {
            //read the Branchname from the .git file
            $stringfromfile = file(base_path() . '/.git/HEAD');
            $firstLine = $stringfromfile[0]; //get the string from the array
            $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string
            $branchname = $explodedstring[2]; //get the one that is always the branch name
        } catch (Exception $e) {
            $branchname = "unknown";
            Log::error($e);
        }
        config(['BRANCHNAME' => $branchname]);
    }
}
