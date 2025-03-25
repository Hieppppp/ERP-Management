<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovementRequest extends FormRequest
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
            'shelves' => [
                "required",
                "array",
            ],
            'shelves.*.id' => [
                'required',
                Rule::exists('shelves'),
            ],
            'shelves.*.quantity' => 'required|numeric|min:0'
        ];
    }
}
