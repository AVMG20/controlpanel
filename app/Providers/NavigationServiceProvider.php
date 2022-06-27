<?php

namespace App\Providers;

use App\Classes\Navigation\NavigationManager;
use App\Classes\Navigation\NavigationItem;
use App\Enums\NavigationLocation;
use App\Helper\NavigationHelper;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{

    /**
     * @var NavigationHelper
     */
    protected NavigationHelper $navigationHelper;

    /**
     * @param $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->navigationHelper = $app->make(NavigationHelper::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $view = view();

        //load navigation options
        $sidebar = $this->loadSidebar();
        $settings = $this->loadSettings();
        $loadedNavigationOptions = array_merge($sidebar, $settings);

        //get current navigation options
        $navigation = $view->shared('navigation') ?? [];

        //push sidebar options into navigation
        /** @var NavigationItem $item */
        foreach ($loadedNavigationOptions as $item) {
            $navigation[$item->getName()] = $item;
        }

        //set new navigation options
        $view->share('navigation', $navigation);

        //set NavigationManager
        $navigationManager = $this->makeNavigationManager($navigation);
        $view->share('navigationManager', $navigationManager);
    }

    /**
     * Load sidebar for core cp
     *
     * @return array
     * @throws Exception
     */
    protected function loadSidebar(): array
    {
        $path = resource_path('views/layouts/parts/navigation/sidebar');

        return $this->navigationHelper->loadNavigationOptionsFromFolder(NavigationLocation::sidebar, $path);
    }


    /**
     * Load settings for core cp
     *
     * @return array
     * @throws Exception
     */
    protected function loadSettings(): array
    {
        $path = resource_path('views/layouts/parts/navigation/settings');

        return $this->navigationHelper->loadNavigationOptionsFromFolder(NavigationLocation::settings, $path);
    }

    /**
     * @param array $navigationOptions
     * @return NavigationManager
     * @throws BindingResolutionException
     */
    protected function makeNavigationManager(array $navigationOptions): NavigationManager
    {
        /** @var NavigationManager $navigationManager */
        $navigationManager = $this->app->make(NavigationManager::class);

        $navigationManager->setNavigationOptions($navigationOptions);

        return $navigationManager;
    }
}
