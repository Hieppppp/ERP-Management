<?php

namespace App\Repositories\SaleOrderDetail;

use App\Repositories\BaseRepository;
use App\Models\SaleOrderDetail;

class SaleOrderDetailRepository extends BaseRepository implements SaleOrderDetailRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(SaleOrderDetail::class);
    }

    public function getHistoricalSales($productId, $day = 30)
    {
        $endDate = now()->subDay();
        $startDate = $endDate->copy()->subDays($day);

        return $this->getModel()
            ->join('sale_orders', 'sale_orders.id', '=', 'sale_order_details.sale_order_id')
            ->where('sale_order_details.product_id', $productId)
            ->whereBetween('sale_orders.created_at', [$startDate, $endDate])
            ->selectRaw('DATE(sale_orders.created_at) as sale_date, SUM(sale_order_details.quantity) as total_quantity')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->pluck('total_quantity', 'sale_date')
            ->toArray();
    }



}
