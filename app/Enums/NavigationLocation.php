<?php

namespace App\Enums;

enum NavigationLocation
{
    /**
     * On the left of the dashboard
     * Only visible in the dashboard view
     */
    case sidebar;

    /**
     * Top bar
     * Only visible in the dashboard view
     */
    case topbar;

    /**
     * Footer
     * Only visible in the dashboard view
     */
    case footer;

    /**
     * Top bar
     * Only visible in the dashboard view
     */
    case settings;
    case profile;
}
