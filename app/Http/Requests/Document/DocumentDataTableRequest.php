<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\BaseDatatableRequest;
use Illuminate\Foundation\Http\FormRequest;

class DocumentDataTableRequest extends BaseDatatableRequest
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
            //
        ];
    }
}
