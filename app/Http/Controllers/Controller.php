<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

class Controller extends BaseController
{
    public Builder $htmlBuilder;

    public function __construct(Builder $htmlBuilder)
    {
        $this->htmlBuilder = $htmlBuilder;
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Check if user has permissions
     * @param string $permission
     * @param string $guardName
     * @return void
     */
    public function can(string $permission, string $guardName = 'web'){
        /** @var User $user */
        $user = Auth::user();

        if (!$user->hasPermissionTo($permission, $guardName)) {
            abort(403, __('User does not have the right permissions.'));
        }
    }
}
