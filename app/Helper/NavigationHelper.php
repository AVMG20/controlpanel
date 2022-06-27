<?php

namespace App\Helper;

use App\Classes\Navigation\NavigationItem;
use App\Enums\NavigationLocation;
use DirectoryIterator;
use Exception;

class NavigationHelper
{
    static array $loadedOptions = [];

    /**
     * Automatically convert blade navigation files to NavigationOption objects
     * Store your navigation files in a folder
     *
     *
     * Filename convention, sort_order - navigation_name .blade.php
     * Starter: 100-core-dashboard.blade.php
     *
     * navigation_name = the name this file identifies as
     * sort_order = the order this file will be displayed in
     * @param NavigationLocation $location location prop
     * @param string $path folder with navigation options
     * @return NavigationItem[]
     * @throws Exception
     * @example 100-example.blade.php
     */
    public function loadNavigationOptionsFromFolder(NavigationLocation $location, string $path): array
    {
        //prevent double loading
        if (array_key_exists($path, self::$loadedOptions,) && !empty(self::$loadedOptions[$path])) return self::$loadedOptions[$path];

        $this->validatePath($path);
        $navigationOptions = [];

        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                //get values
                $name = $this->getNameFromFile($fileinfo);
                $sortOrder = $this->getSortOrderFromFile($fileinfo);
                $blade = file_get_contents($fileinfo->getRealPath());

                $navigationOptions[] = new NavigationItem($name, $location, $blade, $sortOrder);
            }
        }

        self::$loadedOptions[$path] = $navigationOptions;
        return $navigationOptions;
    }

    /**
     * Get name from navigation file
     *
     * @param $fileinfo
     * @return string
     */
    protected function getNameFromFile($fileinfo): string
    {
        $name = $fileinfo->getFileName();

        $exploded = explode('-', $name);
        array_shift($exploded);

        $name_without_sort_order = implode('-', $exploded);

        $exploded = explode('.', $name_without_sort_order);
        return array_shift($exploded);
    }

    /**
     * Get sort_order from navigation file
     *
     * @param $fileinfo
     * @return string
     */
    protected function getSortOrderFromFile($fileinfo): string
    {
        return str($fileinfo->getFilename())->explode('-')->shift();
    }

    /**
     * @param string $path
     * @return void
     * @throws Exception
     */
    protected function validatePath(string $path): void
    {
        if (!file_exists($path)) throw new Exception('Unable to find folder at given path: ' . $path);
    }
}
