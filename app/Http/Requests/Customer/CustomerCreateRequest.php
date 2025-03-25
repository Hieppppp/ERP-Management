<?php

namespace App\Http\Requests\Customer;

use App\Enums\PaymentTermTypeEnum;
use App\Enums\PaymentTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerCreateRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')->where('deleted_at', null)
            ],
            'phone' => [
                'required',
            ],
            'detail_address' => 'required|max:1000',
            'postal_code' => 'required',
            'country' => 'required',
            'province' => 'required',
            'city' => 'required',
            'avatar' => [
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'nullable',
                'max:5120'
            ],
            'contact_url' => [
                'nullable',
                'url'
            ],
            'discount' => 'nullable',
            'company_name' => 'nullable',
            'company_phone' => 'nullable',
            'company_email' => [
                'required',
                'email',
                Rule::unique('customers', 'company_email')->where('deleted_at', null)
            ],
            'company_site' => [
                'nullable',
                'url'
            ],
            'company_country' => 'nullable',
            'company_province' => 'nullable',
            'company_city' => 'nullable',
            'company_address' => 'nullable',
            'company_postal_code' => 'nullable',
            'payment_method' => [
                'nullable',
                Rule::in(PaymentTypeEnum::getValues()),
            ],
            'payment_term' => [
                'nullable',
                Rule::in(PaymentTermTypeEnum::getValues()),
            ],
        ];
    }
}
