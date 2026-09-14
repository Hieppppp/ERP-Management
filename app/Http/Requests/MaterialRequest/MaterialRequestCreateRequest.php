<?php
namespace App\Http\Requests\MaterialRequest;
use Illuminate\Foundation\Http\FormRequest;
class MaterialRequestCreateRequest extends FormRequest
{
 public function authorize(): bool { return true; }
 public function rules(): array { return ['department_id'=>'required|integer|exists:departments,id','needed_at'=>'nullable|date','note'=>'nullable|string|max:1000','items'=>'required|array|min:1','items.*.product_id'=>'required|integer|distinct|exists:products,id','items.*.quantity'=>'required|numeric|gt:0','items.*.note'=>'nullable|string|max:500']; }
}
