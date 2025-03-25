<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\BaseDatatableRequest;

class SupplierDatatableRequest extends BaseDatatableRequest
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
            'includeProductIds' => 'nullable'
        ];
    }
}