<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'country',
        'province',
        'city',
        'detail_address',
        'postal_code',
        'department_id',
        'position_id'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class)->withTrashed();
    }

    public function getAddressAttribute(): string
    {
        return implode(', ', [$this->detail_address, $this->city, $this->province, $this->postal_code, $this->country]);
    }

    /**
     * Get Activity log Options
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty();
    }

}
