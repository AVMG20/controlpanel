<?php

namespace App\Http\Requests\NotificationTemplate\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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

        return $user->hasPermissionTo('admin.users.write');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|min:4|max:30',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|' . Rule::unique('users', 'email'),
            'credits' => 'required|numeric|min:0|max:99999999999',
            'server_limit' => 'sometimes|numeric|max:2147483647|min:0',
            'roles' => 'nullable|array',
            'roles.*' => 'required|exists:roles,id',
        ];
    }
}
