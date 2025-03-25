<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxProduct extends Model
{
    use HasFactory;

    protected $primaryKey = ['tax_id', 'product_id'];

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'tax_id',
        'product_id'
    ];
}
