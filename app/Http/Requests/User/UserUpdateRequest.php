<?php

namespace App\Http\Requests\User;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'username' => [
                'required',
                Rule::unique('users', 'username')->where(fn($query) => $query->whereNotIn('id', [$id])),
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'role' => [
                'nullable',
                Rule::in(UserRole::getValues())
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(fn($query) => $query->whereNotIn('id', [$id])),
            ],
            'permission_ids' => 'nullable|array'
        ];
    }
}
