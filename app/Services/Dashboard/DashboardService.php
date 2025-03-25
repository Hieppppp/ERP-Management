<?php

namespace App\Services\Dashboard;

use App\Enums\SaleOrderStatusEnum;
use App\Repositories\Dashboard\DashboardRepositoryInterface;

class DashboardService implements DashboardServiceInterface
{
    public function __construct(public DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }
    /**
     * Current Stock
     *
     * @return array
     */
    public function currentStock(): array
    {
        return $this->dashboardRepository->currentStock();
    }

    /**
     * Low Stock
     *
     * @return array
     */
    public function lowStock(): array
    {
        return $this->dashboardRepository->lowStock();
    }

    /**
     * Statistic
     *
     * @return array
     */
    public function statistic(): array
    {

        return $this->dashboardRepository->statistic();
    }

    /**
     * Recent Orders
     *
     * @return array
     */
    public function recentOrders(): array
    {
        return $this->dashboardRepository->recentOrders(null);
    }

    /**
     * topSelling
     *
     * @param array $name
     * @return array
     */
    public function topSelling(array $param): array
    {
        return $this->dashboardRepository->topSelling($param);
    }

    /**
     * Pending Orders
     *
     * @return array
     */
    public function pendingOrders(): array
    {
        return $this->dashboardRepository->recentOrders(['status' => [SaleOrderStatusEnum::CONFIRM, SaleOrderStatusEnum::DRAFT]]);
    }
}
