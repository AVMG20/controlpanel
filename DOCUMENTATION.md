## Loading navigation options

Within your `boot` function you can add the `loadNavigation` method. <br />
This method loads all navigations from the specified folder to the specified location.

```php
public function boot()
{
    //location of the folder with sidebar navigation options
    $sidebarNavigation = __DIR__ . '/../resources/sidebar'
    
    $this->loadNavigation(NavigationLocation::sidebar, $sidebarNavigation);
}
```

### Navigation options
For example, you can have the blade files for your menu items in the `sidebar` folder. <br />
In the `sidebar`, you may find a file named `1000-vouchers.blade.php.`


Filename convention: `sort_order` - `navigation_name` .`blade.php` <br/>
Example: `1000-vouchers.blade.php`

`sort_order` = the order this file will be displayed in; check other files to pick a number you like <br/>
`navigation_name` = the name this file identifies as, can be anything


#### Example code for a sidebar navigation option (1000-vouchers.blade.php)
With the power of blade you can add anything laravel and controlpanel have to offer,
including permissions and php code. Allowing you to add anything you want.
```html
@can('controlpanel.vouchers.read')
    <li class="nav-item {{ request()->routeIs('controlpanel.vouchers.index') ? 'active' : '' }}">
        <a href="{{ route('controlpanel.vouchers.index') }}" class="nav-link">
                    <span class="sidebar-icon me-3">
                        <i class="fas fa-money-bill fa-fw"></i>
                    </span>
            <span class="sidebar-text">{{ __('Vouchers') }}</span>
        </a>
    </li>
@endcan
```

#### Example file structure
- resources/sidebar
    - 1000-vouchers.blade.php

### Note
- The folder does not need to be named `sidebar`. You can name it whatever you like
- keep navigation names unique; else, they can get accidentally overridden by other packages.
- sort_order is from low to highest, the lowest value will be on top and the lowest will be at the lowest point <br /> from left to right


