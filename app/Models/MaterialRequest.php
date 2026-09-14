<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class MaterialRequest extends Model
{
    protected $fillable = ['code','department_id','requested_by','approved_by','status','needed_at','note','approval_note','approved_at'];
    protected $casts = ['needed_at' => 'date', 'approved_at' => 'datetime'];
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function items(): HasMany { return $this->hasMany(MaterialRequestItem::class); }
    public function issues(): HasMany { return $this->hasMany(MaterialIssue::class); }
}
