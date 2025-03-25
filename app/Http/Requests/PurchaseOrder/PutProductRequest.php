<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Models\ProductPurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;

class PutProductRequest extends FormRequest
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
            'shelves' => 'required|array',
            'shelves.*.id' => 'required|exists:shelves,id',
            'shelves.*.quantity' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value != null) {
                        if ($this->checkTotalQuantity() == false) {
                            return $fail(__('validation.shelvesQuantityNotMatch'));
                        }
                    }
                }
            ],
        ];
    }

    /**
     * Check Total Quantity
     *
     * @return bool
     */
    public function checkTotalQuantity(): bool
    {
        $shelves = $this->input('shelves');
        $totalQuantity = 0;
        foreach ($shelves as $shelf) {
            $totalQuantity += $shelf['quantity'];
        }

        $purchaseOrderId = $this->route('purchaseOrderId');
        $productId = $this->route('productId');
        $productPurchaseOrder = ProductPurchaseOrder::where('purchase_order_id', $purchaseOrderId)
            ->where('product_id', $productId)->first();

        if ($productPurchaseOrder === null) {
            return false;
        }
        return $totalQuantity == $productPurchaseOrder->received_quantity;
    }
}
