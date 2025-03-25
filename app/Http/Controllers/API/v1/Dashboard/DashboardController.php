<?php

namespace App\Http\Controllers\API\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardServiceInterface $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Current Stock
     *
     * @return JsonResponse
     */
    public function currentStock(): JsonResponse
    {
        $data = $this->dashboardService->currentStock();
        return $this->responseSuccess($data);
    }

    /**
     * Low Stock
     *
     * @return JsonResponse
     */
    public function lowStock(): JsonResponse
    {
        $data = $this->dashboardService->lowStock();
        return $this->responseSuccess($data);
    }

    /**
     * statistic
     *
     * @return JsonResponse
     */
    public function statistic(): JsonResponse
    {
        $data = $this->dashboardService->statistic();
        return $this->responseSuccess($data);
    }

    /**
     * Recent Orders
     *
     * @return JsonResponse
     */
    public function recentOrders(): JsonResponse
    {
        $data = $this->dashboardService->recentOrders();
        return $this->responseSuccess($data);
    }

    /**
     * topSelling
     *
     * @return JsonResponse
     */
    public function topSelling(): JsonResponse
    {
        $param = [
            'startTime' => request()->get('startTime') ?? date('Y-m-d 00:00:00'),
            'endTime' => request()->get('endTime') ?? date('Y-m-d 23:59:59')
        ];
        $data = $this->dashboardService->topSelling($param);
        return $this->responseSuccess($data);
    }

    /**
     * Pending Order
     *
     * @return JsonResponse
     */
    public function pendingOrder(): JsonResponse
    {
        $data = $this->dashboardService->pendingOrders();
        return $this->responseSuccess($data);
    }
}
