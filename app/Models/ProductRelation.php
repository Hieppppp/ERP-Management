<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRelation extends Model
{
    use HasFactory;

    protected $primaryKey = ['parent_product_id', 'child_product_id'];

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'parent_product_id',
        'child_product_id'
    ];
}
