<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
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
        $id = $this->route('product');
        return [
            'name' => [
                'required',
                Rule::unique('products', 'name')->where(fn ($query) => $query->whereNotIn('id', [$id]))->where('deleted_at', null)
            ],
            'unit_id' => [
                'required',
                Rule::exists('units', 'id')
            ],
            'min_quantity' => [
                'required'
            ],
            'max_quantity' => [
                'required'
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
            ],
            'unit_price' => [
                'required'
            ],
            'description' => 'nullable',
            'image' => ['array', 'nullable'],
            'image.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'nullable',
                'max:5120'
            ],
            'suppliers' => ['array', 'nullable'],
            'suppliers.*.id' => [
                'nullable',
                Rule::exists('suppliers', 'id')
            ],
            'suppliers.*.price' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'child_products' => ['array', 'nullable'],
            'child_products.*' => [
                'nullable',
                Rule::exists('products', 'id')
            ],
            'parent_products' => ['array', 'nullable'],
            'parent_products.*' => [
                'nullable',
                Rule::exists('products', 'id')
            ],
        ];
    }
}
