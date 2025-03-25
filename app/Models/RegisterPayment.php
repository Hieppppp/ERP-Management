<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_order_id',
        'date',
        'payment_type',
        'paid_amount',
        'note'
    ];

    public function getDateAttribute($value)
    {
        $carbonDate = Carbon::parse($value);
        return $carbonDate->format('Y-m-d');
    }
}
