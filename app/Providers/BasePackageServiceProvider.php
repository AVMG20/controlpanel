<?php

namespace App\Providers;

use App\Enums\NavigationLocation;
use App\Helper\NavigationHelper;
use Exception;
use Illuminate\Support\ServiceProvider;

class BasePackageServiceProvider extends ServiceProvider
{
    protected NavigationHelper $navigationHelper;

    public function __construct($app)
    {
        $this->navigationHelper = $app->make(NavigationHelper::class);

        parent::__construct($app);
    }

    /**
     * @param NavigationLocation $location Location to display nav options; example: sidebar
     * @param string $directoryPath Path of the directory that holds the navigation options
     * @return void
     * @throws Exception
     */
    public function loadNavigation(NavigationLocation $location, string $directoryPath): void
    {
        $view = view();

        //load navigation from folder to specified location
        $items = $this->navigationHelper->loadNavigationOptionsFromFolder($location, $directoryPath);

        //get current navigation options
        $navigation = $view->shared('navigation') ?? [];

        //push sidebar options into already loaded navigation options
        foreach ($items as $item) {
            $navigation[$item->getName()] = $item;
        }

        $view->share(['navigation' => $navigation]);
    }


}
