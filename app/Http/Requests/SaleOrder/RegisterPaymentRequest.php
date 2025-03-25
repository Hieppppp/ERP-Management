<?php

namespace App\Http\Requests\SaleOrder;

use App\Models\SaleOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class RegisterPaymentRequest extends FormRequest
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
            "payment_type" => "required|string",
            "date" => [
                "required",
                "date",
                function($attribute, $value, $fail)
                {
                    $dateValue = Carbon::parse($value)->startOfDay();
                    $invoiceCreationDate = Carbon::parse($this->getInvoiceDate())->startOfDay();
                    $currentDate = Carbon::now()->startOfDay();
                    if ($dateValue->lt($invoiceCreationDate) || $dateValue->gt($currentDate))
                    {
                        return $fail(__('validation.checkDate'));
                    }
                },
            ],
            "paid_amount" => "required|numeric"
        ];
    }
    
    public function getInvoiceDate()
    {
        $id = $this->route('invoice');
        return SaleOrder::where('id', $id)->value('created_at');
    }

}
