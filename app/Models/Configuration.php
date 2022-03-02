<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'setup_price',
        'minimum_price',
        'memory',
        'cpu',
        'swap',
        'disk',
        'io',
        'databases',
        'backups',
        'allocations',
    ];

    protected $casts = [
        'price' => 'float',
        'setup_price' => 'float',
        'minimum_price' => 'float',
        'memory' => 'int',
        'cpu' => 'int',
        'swap' => 'int',
        'disk' => 'int',
        'io' => 'int',
        'databases' => 'int',
        'backups' => 'int',
        'allocations' => 'int',
    ];
}
