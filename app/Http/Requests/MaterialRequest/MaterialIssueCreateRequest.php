<?php
namespace App\Http\Requests\MaterialRequest;
use Illuminate\Foundation\Http\FormRequest;
class MaterialIssueCreateRequest extends FormRequest
{
 public function authorize(): bool { return true; }
 public function rules(): array { return ['note'=>'nullable|string|max:1000','allocations'=>'required|array|min:1','allocations.*.item_id'=>'required|integer|exists:material_request_items,id','allocations.*.product_location_id'=>'required|integer|exists:product_locations,id','allocations.*.quantity'=>'required|numeric|gt:0']; }
}
