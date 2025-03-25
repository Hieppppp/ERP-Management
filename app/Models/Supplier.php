<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'logo',
        'email',
        'phone',
        'detail_address',
        'country',
        'province',
        'city',
        'site',
        'code',
        'postal_code'
    ];

    protected $appends = [
        'logo_url'
    ];

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset(Storage::url("suppliers/{$this->logo}")) : null;
    }

    public function getAddressAttribute()
    {
        return implode(', ', [$this->detail_address, $this->city, $this->province, $this->postal_code, $this->country]);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_suppliers')->withPivot(['unit_cost']);
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
