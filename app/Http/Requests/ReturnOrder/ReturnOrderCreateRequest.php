<?php

namespace App\Http\Requests\ReturnOrder;

use App\Models\ProductLocation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ReturnOrderCreateRequest extends FormRequest
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
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'scheduled_date' => 'nullable|date|after_or_equal:today',
            'products' => 'required|array',
            'products.*.product_location_id' => 'required|exists:product_locations,id',
            'products.*.demand_quantity' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value != null) {
                        $index = (int) Str::between($attribute, 'products.', '.demand_quantity');
                        $productLocationId = $this->input("products.$index.product_location_id");
                        $productLocation = ProductLocation::find($productLocationId);
                        if ($value > $productLocation->quantity) {
                            $fail(__('validation.maxDemandQuantity'));
                        }
                    }
                }
            ],
        ];
    }
}
