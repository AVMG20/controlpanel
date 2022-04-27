<?php

namespace App\Classes\Navigation;

use App\Enums\NavigationLocation;

class NavigationOption
{
    public string $name;
    public NavigationLocation $location;
    public int $sort_order;
    public string $blade;

    /**
     * @param string $name
     * @param NavigationLocation $location
     * @param int $sort_order
     * @param string $blade
     */
    public function __construct(string $name, NavigationLocation $location, string $blade = '', int $sort_order = 0)
    {
        $this->name = $name;
        $this->location = $location;
        $this->sort_order = $sort_order;
        $this->blade = $blade;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return NavigationLocation
     */
    public function getLocation(): NavigationLocation
    {
        return $this->location;
    }

    /**
     * @param NavigationLocation $location
     */
    public function setLocation(NavigationLocation $location): void
    {
        $this->location = $location;
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sort_order;
    }

    /**
     * @param int $sort_order
     */
    public function setSortOrder(int $sort_order): void
    {
        $this->sort_order = $sort_order;
    }

    /**
     * @return string
     */
    public function getBlade(): string
    {
        return $this->blade;
    }

    /**
     * @param string $blade
     */
    public function setBlade(string $blade): void
    {
        $this->blade = $blade;
    }
}
