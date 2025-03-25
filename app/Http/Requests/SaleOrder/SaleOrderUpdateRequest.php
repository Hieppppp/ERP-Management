<?php

namespace App\Http\Requests\SaleOrder;

use App\Enums\DeliverMethodEnum;
use App\Enums\PaymentTermTypeEnum;
use App\Enums\SaleOrderStatusEnum;
use App\Models\ProductLocation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SaleOrderUpdateRequest extends FormRequest
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
            'customer_id' => 'required',
            'customer_email' => 'required',
            'customer_phone' => 'required',
            'deliver_address' => 'required',
            'customer_address' => 'required',
            'tax_data' => 'nullable|array',
            'tax_data.*.code' => 'required',
            'tax_data.*.rate' => 'required|numeric',
            'order_status' => [
                'required',
                Rule::in([SaleOrderStatusEnum::DRAFT, SaleOrderStatusEnum::CONFIRM]),
            ],
            'delivery_method' => [
                'required',
                Rule::in(DeliverMethodEnum::getValues()),
            ],
            'payment_term' => [
                'required',
                Rule::in(PaymentTermTypeEnum::getValues()),
            ],
            'products' => ['required', 'array'],
            'products.*.id' => [
                'required',
                'exists:products,id'
            ],
            'products.*.discount_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
            'products.*.unit_price' => [
                'required',
                'numeric',
            ],
            'products.*.order_quantity' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'note' => 'nullable',
            'warehouse_id' => [
                'required',
                'exists:warehouses,id'
            ],
        ];
    }
}
