<?php

namespace App\Http\Requests\Server;

use App\Models\Configuration;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->hasPermissionTo('admin.servers.write');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|max:99999999999|min:0',
            'memory' => 'required|integer|max:1000000|min:0',
            'cpu' => 'required|integer|max:1000000|min:0',
            'swap' => 'required|integer|max:1000000|min:0',
            'disk' => 'required|integer|max:1000000|min:0',
            'io' => 'required|integer|max:1000|min:10',
            'databases' => 'required|integer|max:1000000|min:0',
            'backups' => 'required|integer|max:1000000|min:0',
            'allocations' => 'required|integer|max:1000000|min:0'
        ];
    }
}
