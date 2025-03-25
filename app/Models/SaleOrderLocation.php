<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_order_detail_id',
        'product_location_id',
        'quantity',
    ];

    public $timestamps = false;
}
