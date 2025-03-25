<?php

namespace App\Models;

use App\Enums\PurchaseOrderStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'status',
        'supplier_id',
        'warehouse_id',
        'scheduled_date',
        'created_by',
        'updated_by',
        'received_note',
        'received_date',
        'send_date',
        'code',
        'batch_code',
        'receipt_code',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_purchase_orders')
            ->withPivot(
                'quantity',
                'unit_cost',
                'received_quantity',
                'received'
            );
    }

    public function purchaseProductShelves()
    {
        return $this->hasMany(PurchaseProductShelve::class, 'purchase_order_id');
    }

    public function productLocations()
    {
        return $this->hasMany(ProductLocation::class, 'purchase_order_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $carbonDate = Carbon::parse($value);
        return $carbonDate->format('Y-m-d H:i:s');
    }

    public function returnOrders()
    {
        return $this->hasMany(ReturnOrder::class, 'purchase_order_id');
    }
}
