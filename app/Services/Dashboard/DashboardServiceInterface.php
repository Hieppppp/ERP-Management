<?php

namespace App\Services\Dashboard;

use App\Services\BaseServiceInterface;

interface DashboardServiceInterface
{
    /**
     * Current Stock
     *
     * @return array
     */
    public function currentStock(): array;

    /**
     * Low Stock
     *
     * @return array
     */
    public function lowStock(): array;

    /**
     * Statistic
     *
     * @return array
     */
    public function statistic(): array;

    /**
     * Recent Orders
     *
     * @return array
     */
    public function recentOrders(): array;

    /**
     * topSelling
     *
     * @param array $name
     * @return array
     */
    public function topSelling(array $param): array;

    /**
     * pendingOrders
     *
     * @return array
     */
    public function pendingOrders(): array;
}
