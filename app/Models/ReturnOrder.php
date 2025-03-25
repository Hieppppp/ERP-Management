<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'status',
        'purchase_order_id',
        'scheduled_date',
        'note',
        'code'
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function returnOrderDetails(): HasMany
    {
        return $this->hasMany(ReturnOrderDetails::class, 'return_order_id');
    }

    public function productLocations(): BelongsToMany
    {
        return $this->belongsToMany(ProductLocation::class, 'return_order_details')->withPivot(
            'demand_quantity',
            'quantity',
        );
    }

    public function getCreatedAtAttribute($value)
    {
        $carbonDate = Carbon::parse($value);
        return $carbonDate->format('Y-m-d H:i:s');
    }
}
