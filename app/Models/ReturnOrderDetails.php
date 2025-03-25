<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnOrderDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'return_order_id',
        'product_location_id',
        'demand_quantity',
        'quantity'
    ];

    public function returnOrder(): BelongsTo
    {
        return $this->belongsTo(ReturnOrder::class, 'return_order_id');
    }

    public function productLocations(): BelongsTo
    {
        return $this->belongsTo(ProductLocation::class, 'product_location_id');
    }
}
