<?php

namespace App\Http\Requests\Province;

use App\Models\Province;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProvinceCreateRequest extends FormRequest
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
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value != null) {
                        $checkUniqueProvince = $this->checkUniqueProvince($value);
                        if ($checkUniqueProvince == false) {
                            return $fail(__('validation.unique', [
                                'field' => __('translation.province.name')
                            ]));
                        }
                    }
                }
            ],
            'country_id' => [
                'required',
                Rule::exists('countries', 'id')
            ]
        ];
    }

    public function checkUniqueProvince($value)
    {
        $countryId = request('country_id');
        if (!$countryId) {
            return false;
        }
        $province = Province::where('name', $value)->where('country_id', $countryId)->first();
        if ($province) {
            return false;
        }
        return true;
    }
}
