<?php
namespace App\Http\Requests\MaterialRequest;
use Illuminate\Foundation\Http\FormRequest;
class MaterialRequestDecisionRequest extends FormRequest
{
 public function authorize(): bool { return true; }
 public function rules(): array { return ['note'=>'nullable|string|max:1000']; }
}
