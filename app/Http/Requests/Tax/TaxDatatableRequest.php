<?php

namespace App\Http\Requests\Tax;

use App\Http\Requests\BaseDatatableRequest;

class TaxDatatableRequest extends BaseDatatableRequest
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
            'draw' => 'required',
            'start' => 'required|integer',
            'length' => 'required|integer',
            'search' => 'required',
            'columns' => 'required',
            'order' => 'required',
        ];
    }
}
