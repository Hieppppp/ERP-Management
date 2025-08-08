<?php

namespace App\Services\SaleOrderDetail;

use App\Services\BaseServiceInterface;

interface SaleOrderDetailServiceInterface extends BaseServiceInterface
{
    public function getHistoricalSales($productId);
}
