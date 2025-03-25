<?php

namespace App\Http\Requests\SaleOrder;

use App\Models\SaleOrderDetail;
use Illuminate\Foundation\Http\FormRequest;

class SaleOrderAddStockRequest extends FormRequest
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
        $saleOrderId = $this->route('id');
        $productId = $this->route('product_id');
        return [
            "inventories" => [
                "required",
                "array",
                function ($attribute, $value, $fail) use ($saleOrderId, $productId) {
                    $saleOrderDetail = SaleOrderDetail::where('sale_order_id', $saleOrderId)->where('product_id', $productId)->first();
                    if (!$saleOrderDetail || array_sum(array_column($value, 'select_quantity')) != $saleOrderDetail['quantity']) {
                        return $fail(__('message.totalStockQuantityNotMatch'));
                    }
                }
            ],
            "inventories.*.id" => [
                "required",
                "exists:product_locations,id"
            ],
            "inventories.*.select_quantity" => [
                "required",
                "numeric"
            ]
        ];
    }
}
