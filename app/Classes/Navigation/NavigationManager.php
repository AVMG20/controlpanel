<?php

namespace App\Classes\Navigation;

use App\Enums\NavigationLocation;
use Illuminate\Support\Facades\Blade;

class NavigationManager
{
    public array $navigationOptions = [];

    /**
     * @param array $navigationOptions
     * @return NavigationManager
     */
    public function setNavigationOptions(array $navigationOptions): NavigationManager
    {
        $this->navigationOptions = $navigationOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getAllNavigationOptions(): array
    {
        return $this->navigationOptions;
    }

    /**
     * Get the navigation options for the given location.
     *
     * @param NavigationLocation $location
     * @return array
     */
    public function getNavigationOptions(NavigationLocation $location): array
    {
        //filter navigationOptions by location property
        $options = array_filter($this->navigationOptions, function (NavigationOption $option) use ($location) {
            return $option->getLocation() === $location;
        });

        //sort navigationOptions by sort_order property
        usort($options, function (NavigationOption $option1, NavigationOption $option2) {
            return $option1->getSortOrder() <=> $option2->getSortOrder();
        });

        return $options;
    }

    /**
     * Get HTML for navigation location
     *
     * @param NavigationLocation $location
     * @return string
     */
    public function render(NavigationLocation $location): string
    {
        $options = $this->getNavigationOptions($location);

        $blade = '';

        /** @var NavigationOption $option */
        foreach ($options as $option) {
            $blade .= $option->getBlade();
        }

        return Blade::render($blade);
    }
}
