<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentUpdateRequest extends FormRequest
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
            'name' => [
                'required',
                'max:100',
                Rule::unique('departments', 'name')->where(fn ($query) => $query->whereNotIn('id', [$id]))->where('deleted_at', null),
            ],
            'description' => [
                'nullable',
                'max:1000'
            ]
        ];
    }
}
