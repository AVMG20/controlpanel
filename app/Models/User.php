<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'pterodactyl_id',
        'credits',
        'server_limit',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'credits' => 'float',
        'server_limit' => 'int',
    ];

    /**
     * Get credits formatted
     *
     * @return float
     */
    public function getCreditsFormattedAttribute(): float
    {
        return number_format($this->attributes['credits'], '2', '.', '');
    }

    /**
     * get usage formatted
     *
     * @return float
     */
    public function getCreditUsageAttribute(): float
    {
        $usage = $this->servers()
            ->where('suspended', '=', '0')
            ->sum('price');

        return number_format($usage, '2', '.', '');
    }

    /**
     * get server count
     *
     * @return int
     */
    public function getServerCountAttribute(): int
    {
        return $this->servers()->count();
    }

    /**
     * Gravatar's
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->email)));
    }

    /**
     * @return HasMany
     */
    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }

}
