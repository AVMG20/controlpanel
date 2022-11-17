<?php

namespace App\Http\Requests\NotificationTemplate\Role;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class RoleUpdateRequest extends FormRequest
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
            'name' => ["required", "string", "max:60", Rule::unique('roles')->ignore($this->role->id)],
            'color' => "required|string|max:60",
            'permissions' => 'nullable|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
    }

    protected function prepareForValidation()
    {
        // Do not add any other permissions if * is chosen.
        $permissions = $this->permissions;

        if (in_array("1", $permissions) && count($permissions) > 1 || (!in_array("1", $permissions) && count($permissions) === Permission::count() - 1)) {
            $this->request->add(['permissions' => ["1"]]);
        }
    }
}
