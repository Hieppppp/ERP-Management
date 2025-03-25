<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierEditRequest extends FormRequest
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
        $supplierId = $this->route('supplier');
        return [
            'name' => 'required',
            'country' => 'required',
            'province' => 'required',
            'city' => 'required',
            'detail_address' => 'required|max:1000',
            'phone' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers', 'email')->where(fn($query) => $query->whereNotIn('id', [$supplierId]))->where('deleted_at', null),
            ],
            'site' => [
                'nullable',
                'url'
            ],
            'logo' => [
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'nullable',
                'max:5120'
            ],
            'postal_code' => 'required',
        ];
    }
}
