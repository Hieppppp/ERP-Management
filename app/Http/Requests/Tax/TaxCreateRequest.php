<?php

namespace App\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxCreateRequest extends FormRequest
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
            'name'=> [
                'required',
                'max:200'
            ],
            'rate'=> [
                'required',
                'between:0,100',
                'numeric'
            ],
            'code'=> [
                'required',
                'max:50',
                Rule::unique('taxes', 'code')
            ],
            'description'=> [
                'nullable',
                'max:1000'
            ],
        ];
    }
}
