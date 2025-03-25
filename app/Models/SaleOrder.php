<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'code',
        'invoice_code',
        'receipt_code',
        'customer_email',
        'customer_phone',
        'customer_address',
        'deliver_address',
        'payment_term',
        'tax_rate',
        'order_status',
        'note',
        'delivery_method',
        'tax_info',
        'receipt_status',
        'warehouse_id',
        'total_amount',
        'deliver_date'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'sale_order_details')->withPivot('quantity', 'discount_rate', 'unit_price');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $carbonDate = Carbon::parse($value);
        return $carbonDate->format('Y-m-d H:i:s');
    }

    /**
     * Register Payments
     *
     * @return HasMany
     */
    public function registerPayments(): HasMany
    {
        return $this->hasMany(RegisterPayment::class, 'sale_order_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
