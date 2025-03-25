<?php

namespace App\Http\Requests\SaleOrder;

use App\Enums\SaleOrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleOrderUpdateStatusRequest extends FormRequest
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
            'order_status' => [
                'required',
                Rule::in(
                    collect(SaleOrderStatusEnum::getValues())->filter(function ($value) {
                        return $value != SaleOrderStatusEnum::DRAFT;
                    })
                )
            ],
            'note' => 'nullable'
        ];
    }
}
