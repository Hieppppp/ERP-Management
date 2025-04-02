<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeCreateRequest extends FormRequest
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
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->where('deleted_at', null)
            ],
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')
            ],
            'position_id' => [
                'required',
                Rule::exists('positions', 'id')
            ],
            'country' => 'required',
            'province' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'postal_code' => 'required',
            'detail_address' => 'required'
        ];
    }
}
