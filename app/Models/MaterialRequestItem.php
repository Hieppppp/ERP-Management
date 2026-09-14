<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class MaterialRequestItem extends Model
{
    protected $fillable = ['material_request_id','product_id','requested_quantity','issued_quantity','note'];
    public function request(): BelongsTo { return $this->belongsTo(MaterialRequest::class, 'material_request_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
