<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'product_location_id',
        'user_id',
        'product_id',
        'before_quantity',
        'after_quantity',
        'action',
        'note',
        'product_purchase_order_id',
    ];
    const UPDATED_AT = null;
    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function productLocation(): BelongsTo
    {
        return $this->belongsTo(ProductLocation::class);
    }
}
