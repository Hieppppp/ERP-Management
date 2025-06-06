<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'forecast_date',
        'predicted_quantity',
        'trend_percentage',
        'recommended_quantity'
    ];
}
