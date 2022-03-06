<?php

namespace Database\Seeders;

use App\Enums\NavigationLocation;
use App\Helper\NavigationHelper;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    private NavigationHelper $helper;

    public function __construct(NavigationHelper $helper)
    {
        $this->helper = $helper;
    }

    public function run()
    {
        //import sidebar navigation
        $this->helper->importNavigationOptionsFromFolder(NavigationLocation::sidebar,__DIR__ . '/../../resources/views/layouts/parts/sidebar');
    }
}
