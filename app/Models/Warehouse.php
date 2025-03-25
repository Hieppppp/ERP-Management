<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Warehouse extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'detail_address',
        'city',
        'province',
        'country',
        'postal_code',
        'code',
        'contact'
    ];

    /**
     * shelves
     *
     * @return HasMany
     */
    public function shelves(): HasMany
    {
        return $this->hasMany(Shelve::class);
    }

    public function getAddressAttribute()
    {
        return implode(', ', [$this->detail_address, $this->city, $this->province, $this->postal_code, $this->country]);
    }

    public function getUpdatedAtAttribute($value)
    {
        $carbonDate = Carbon::parse($value);
        return $carbonDate->format('Y-m-d H:i:s');
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
