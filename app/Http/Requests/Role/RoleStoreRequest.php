<?php

namespace App\Http\Requests\Role;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RoleStoreRequest extends FormRequest
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

        return $user->hasPermissionTo('admin.roles.write');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ["required", "string", "max:60", "unique:roles"],
            'color' => "required|string|max:60",
            'permissions' => 'nullable|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
    }
}
