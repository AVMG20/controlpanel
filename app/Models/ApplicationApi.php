<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasPermissions;

class ApplicationApi extends Model
{
    use HasFactory, HasPermissions;

    protected $fillable = ['token', 'memo', 'last_used'];

    protected $dates = ['last_used'];


    protected $guard_name = 'api';


    public static function boot()
    {
        parent::boot();

        static::creating(function (ApplicationApi $applicationApi) {
            $applicationApi->token = Str::random(60);
        });
    }

    public function updateLastUsed(){
        $this->update(['last_used' => now()]);
    }
}
