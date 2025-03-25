<?php

namespace App\Repositories\Dashboard;

use App\Repositories\BaseRepositoryInterface;

interface DashboardRepositoryInterface
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
     * @param array | null $name
     * @return array
     */
    public function recentOrders(array | null $params): array;

    /**
     * topSelling
     *
     * @param array $name
     * @return array
     */
    public function topSelling(array $param): array;
}
