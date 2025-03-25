<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProductShelve extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'shelve_id',
        'quantity',
    ];

    public function shelve()
    {
        return $this->belongsTo(Shelve::class);
    }
}
