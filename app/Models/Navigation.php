<?php

namespace App\Models;

use App\Enums\NavigationLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;

class Navigation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'blade',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'int'
    ];

    /**
     * Navigation options are stored in the database
     *
     * @param NavigationLocation $location
     * @return string
     */
    public static function getNavigation(NavigationLocation $location): string
    {
        $blade = '';

        $navigationOptions = Cache::rememberForever('navigation:' . $location->name, function () use ($location) {
            return self::query()
                ->where('location', '=', $location->name)
                ->orderBy('sort_order')
                ->get();
        });

        foreach ($navigationOptions as $option) {
            $blade .= $option->blade;
        }

        return Blade::render($blade);

    }
}
