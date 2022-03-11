<?php

namespace App\Helper;

use App\Enums\NavigationLocation;
use App\Models\Navigation;
use DirectoryIterator;
use Exception;

class NavigationHelper
{
    /**
     * Automatically import navigation options from a folder
     * Store your navigation option inside a file within the folder
     *
     * @note THIS DOES AUTOMATICALLY UPDATE NAVIGATIONS
     *
     * Filename convention, sort_order - name .blade.php
     * Example: 100-core-dashboard.blade.php
     *
     * 100          = the sort order
     * core-home    = the name of the navigation option
     *
     *
     * @param NavigationLocation $location location prop
     * @param string $path folder with navigation options
     * @return void
     * @throws Exception
     */
    public function importNavigationOptionsFromFolder(NavigationLocation $location, string $path)
    {
        $this->validatePath($path);

        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {

                //get values
                $name = $this->getNameFromFile($fileinfo);
                $sortOrder = $this->getSortOrderFromFile($fileinfo);
                $blade = file_get_contents($fileinfo->getRealPath());

                //import navigation options
                Navigation::query()->updateOrCreate([
                    'name' => $name
                ], [
                    'location' => $location->name,
                    'sort_order' => $sortOrder,
                    'blade' => $blade,
                ]);
            }
        }
    }


    /**
     * Same as import, but instead of remove options
     * Can mainly be used for the installation of a package
     *
     * @throws Exception
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

        $exploded = explode('-' , $name);
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
