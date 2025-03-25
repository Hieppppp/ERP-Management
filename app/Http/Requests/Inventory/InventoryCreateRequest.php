<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryCreateRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'shelves' => 'required|array',
            'shelves.*.id' => [
                'required',
                Rule::exists('shelves')->where(function (Builder $query) {
                    $warehouseId = $this->input('warehouse_id');
                    return $query->where('warehouse_id', $warehouseId);
                }),
            ],
            'shelves.*.quantity' => 'required|numeric|min:0'
        ];
    }
}
