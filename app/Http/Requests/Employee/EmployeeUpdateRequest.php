<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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
        $id = $this->route('employee');
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->where(fn ($query) => $query->whereNotIn('id', [$id]))->where('deleted_at', null)
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
            'detail_address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'phone' => 'required',
        ];
    }
}
