<?php

namespace App\Http\Requests\Inventory;

use App\Models\ProductLocation;
use Illuminate\Foundation\Http\FormRequest;

class InventoryUpdateRequest extends FormRequest
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
        $id = $this->route('inventory');
        return [
            'current_quantity' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($id) {
                    $currentInventory = ProductLocation::find($id);
                    if ($value != $currentInventory->quantity) {
                        return $fail(__('validation.quantityMismatch'));
                    };
                }
            ],
            'new_quantity' => 'required|numeric|min:0',
            'note' => 'nullable'
        ];
    }
}
