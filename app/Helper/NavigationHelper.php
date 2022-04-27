<?php

namespace App\Helper;

use App\Classes\Navigation\NavigationOption;
use App\Enums\NavigationLocation;
use App\Models\Navigation;
use DirectoryIterator;
use Exception;

class NavigationHelper
{
    static array $loadedOptions = [];


    /**
     * Automaitcally convert blade navigation files to NavigationOption objects
     * Store your navigation files in a folder
     *
     *
     * Filename convention, sort_order - navigation_name .blade.php
     * Example: 100-core-dashboard.blade.php
     *
     * navigation_name = the name this file identifies as
     * sort_order = the order this file will be displayed in
     * @example 100-example.blade.php
     *
     * @param NavigationLocation $location location prop
     * @param string $path folder with navigation options
     * @return array
     * @throws Exception
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

                $navigationOptions[] = new NavigationOption($name, $location, $blade, $sortOrder);
            }
        }

        self::$loadedOptions[$path] = $navigationOptions;
        return $navigationOptions;
    }


    /**
     * Same as import, but instead of remove options
     * Can mainly be used for the installation of a package
     *
     * @throws Exception
     * @deprecated use importNavigationOptionsFromFolder instead
     */
    public function removeNavigationOptionsFromFolder(string $path)
    {
        $this->validatePath($path);

        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {

                //import navigation options
                $name = $this->getNameFromFile($fileinfo);
                Navigation::query()->where('name', '=', $name)->delete();
            }
        }
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
