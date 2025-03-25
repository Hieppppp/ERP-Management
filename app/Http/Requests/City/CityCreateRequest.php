<?php

namespace App\Http\Requests\City;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityCreateRequest extends FormRequest
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
                        $checkUniqueCity = $this->checkUniqueCity($value);
                        if ($checkUniqueCity == false) {
                            return $fail(__('validation.unique', [
                                'field' => __('translation.city.name')
                            ]));
                        }
                    }
                }
            ],
            'province_id' => [
                'required',
                Rule::exists('provinces', 'id')
            ]
        ];
    }

    public function checkUniqueCity($value)
    {
        $provinceId = request('province_id');
        if (!$provinceId) {
            return false;
        }
        $city = City::where('name', $value)->where('province_id', $provinceId)->first();
        if ($city) {
            return false;
        }
        return true;
    }
}
