<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'avatar',
        'detail_address',
        'postal_code',
        'country',
        'province',
        'city',
        'code',
        'contact_url',
        'discount',
        'company_name',
        'company_phone',
        'company_email',
        'company_site',
        'company_country',
        'company_province',
        'company_city',
        'company_address',
        'company_postal_code',
        'payment_method',
        'payment_term',
    ];

    protected $appends = [
        'avatar_url',
        'name',
    ];

    /**
     * getProfilePictureAttribute
     *
     * @return string|null
     */
    public function getAvatarUrlAttribute(): string|null
    {
        return $this->avatar ? asset(Storage::url("customers/{$this->avatar}")) : null;
    }

    public function getAddressAttribute()
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

    public function getNameAttribute(): string|null
    {
        return implode(' ', [$this->first_name, $this->last_name]);
    }
}
