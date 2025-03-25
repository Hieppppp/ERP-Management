<?php

namespace App\Http\Requests\ReturnOrder;

use App\Enums\ReturnOrderStatusEnum as Status;
use App\Models\ProductLocation;
use App\Models\ReturnOrderDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReturnOrderUpdateRequest extends FormRequest
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
            'scheduled_date' => 'nullable|date|after_or_equal:today',
            'note' => 'nullable',
            'status' => [
                'required',
                Rule::in([Status::CANCEL, Status::DONE])
            ],
            'products' => 'nullable|array',
            'products.*.return_order_detail_id' => 'required|exists:return_order_details,id',
            'products.*.quantity' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($value != null) {
                        $index = (int) Str::between($attribute, 'products.', '.quantity');

                        $orderDetailId = $this->input("products.$index.return_order_detail_id");
                        $returnOrderDetail = ReturnOrderDetails::find($orderDetailId);
                        $demandQuantity = $returnOrderDetail->demand_quantity;
                        $stockQuantity = ProductLocation::find($returnOrderDetail->product_location_id)->quantity;

                        $maxValue = $demandQuantity > $stockQuantity ? $stockQuantity : $demandQuantity;
                        if ($value > $maxValue) {
                            $fail(__('validation.maxQuantity'));
                        }
                    }
                }
            ],
        ];
    }
}
