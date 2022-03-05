<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ApplicationApiRequest extends FormRequest
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

        return $user->hasPermissionTo('admin.api.write');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'memo' => ["required", "string", "max:60"],
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
    }
}
