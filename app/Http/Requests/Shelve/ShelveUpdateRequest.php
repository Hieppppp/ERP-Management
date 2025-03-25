<?php

namespace App\Http\Requests\Shelve;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShelveUpdateRequest extends FormRequest
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
        $shelveId = $this->route('shelve');
        return [
            'name' => [
                'required',
                Rule::unique('shelves', 'name')
                    ->where(function ($query) {
                        return $query->where('warehouse_id', $this->input('warehouse_id'))
                                     ->where('deleted_at', null);
                    })
                    ->ignore($shelveId)
            ],
            'warehouse_id' => [
                'required',
                Rule::exists('warehouses', 'id')
            ],
            'location' => 'nullable'
        ];
    }
}
