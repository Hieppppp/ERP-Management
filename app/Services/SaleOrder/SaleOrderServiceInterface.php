<?php

namespace App\Services\SaleOrder;

use App\Common\Entity\DatatableParams;
use App\Models\RegisterPayment;
use App\Models\SaleOrder;
use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

interface SaleOrderServiceInterface extends BaseServiceInterface
{
    /**
     * registerPayment
     *
     * @param  array $params
     * @param  string $id
     * @return RegisterPayment
     */
    public function registerPayment(array $params, string $id): RegisterPayment;

    /**
     * getOrderStatusCount
     *
     * @return SaleOrder
     */
    public function getOrderStatusCount(): SaleOrder;

    /**
     * getSaleOrderDetails
     *
     * @param  int $saleOrderId
     * @return Collection
     */
    public function getSaleOrderDetails(int $saleOrderId): Collection;

    /**
     * Get Invoice List
     *
     * @param  DatatableParams $datatableParams
     * @return Paginator|LengthAwarePaginator
     */
    public function getInvoiceList(DatatableParams $datatableParams): Paginator|LengthAwarePaginator;

    /**
     * Get Total Amount
     *
     * @param  string $id
     * @return array
     */
    public function getTotalAmount(string $id): array;

    /**
     * updateStatus
     *
     * @param  array $params
     * @param  int $id
     * @return SaleOrder
     */
    public function updateStatus(array $params, int $id): SaleOrder;

    /**
     * validateROG
     *
     * @param  int $id
     * @param  array $params
     * @return SaleOrder
     */
    public function validateROG(int $id, array $params): SaleOrder;

    /**
     * addStock
     *
     * @param  array $params
     * @param  int $orderId
     * @param  int $productId
     * @return void
     */
    public function addStock(array $params, int $orderId, int $productId): void;

    /**
     * getStockAssignmentData
     *
     * @param  int $saleOrderDetailId
     * @return Collection|array
     */
    public function getStockAssignmentData(int $saleOrderDetailId): Collection|array;

    /**
     * Get Invoice PDF
     *
     * @param  string $id
     * @return array
     */
    public function getInvoicePDF(string $id): array;

    /**
     * Send Invoice PDF
     *
     * @param  string $id
     * @return void
     */
    public function sendInvoicePDF(string $id): void;

    public function export();

    public function getRevenueDataForChary($year);


}
