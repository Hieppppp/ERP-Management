<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'country_id'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'tax_provinces');
    }
}
