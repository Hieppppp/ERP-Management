<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Models\ProductPurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseOrderReceiveProductRequest extends FormRequest
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
            'products' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $sku = array_column($value, 'sku');
                    if (count($sku) !== count(array_unique($sku))) {
                        return $fail(__('validation.uniqueSku'));
                    };
                }
            ],
            'products.*.id' => [
                'required',
                Rule::exists('product_purchase_orders', 'product_id')->where('purchase_order_id', $this->route('id')),
            ],
            'products.*.received_quantity' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($value != null) {
                        if ($this->checkMaxReceivedQuantity($attribute, $value) == false) {
                            return $fail(__('validation.maxReceivedQuantity'));
                        }
                    }
                }
            ],
            'products.*.sku' => [
                'nullable',
                Rule::unique('product_suppliers', 'sku')
            ],
            'received_note' => 'nullable|string',
        ];
    }

    public function checkMaxReceivedQuantity($attribute, $value)
    {
        $product = $this->input('products')[$this->getProductIndex($attribute)];
        $productId = $product['id'];
        $purchaseOrderId = $this->route('id');

        $productPurchaseOrder = ProductPurchaseOrder::where('purchase_order_id', $purchaseOrderId)
            ->where('product_id', $productId)->first();
        if ($productPurchaseOrder === null) {
            return false;
        }
        return $value <= $productPurchaseOrder->quantity;
    }

    public function getProductIndex($attribute)
    {
        preg_match('/\d+/', $attribute, $matches);
        return $matches[0] ?? null;
    }
}
