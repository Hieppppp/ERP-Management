<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class MaterialIssue extends Model
{
    protected $fillable = ['material_request_id','issued_by','note'];
    public function request(): BelongsTo { return $this->belongsTo(MaterialRequest::class, 'material_request_id'); }
    public function lines(): HasMany { return $this->hasMany(MaterialIssueLine::class); }
}
