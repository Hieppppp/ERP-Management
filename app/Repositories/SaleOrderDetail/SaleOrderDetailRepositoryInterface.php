<?php

namespace App\Repositories\SaleOrderDetail;

use App\Repositories\BaseRepositoryInterface;

interface SaleOrderDetailRepositoryInterface extends BaseRepositoryInterface
{
    public function getHistoricalSales($productId, $day = 30);
}
