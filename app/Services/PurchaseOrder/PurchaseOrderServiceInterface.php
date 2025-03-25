<?php

namespace App\Services\PurchaseOrder;

use App\Common\Entity\DatatableParams;
use App\Models\PurchaseOrder;
use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

interface PurchaseOrderServiceInterface extends BaseServiceInterface
{
    /**
     * Get Total By Status
     *
     * @return array
     */
    public function getTotalByStatus(): array;

    /**
     * Send Purchase Order
     *
     * @param  string $id
     * @return bool
     */
    public function sendPurchaseOrder(string $id): bool;

    /**
     * Receive Product
     *
     * @param  array $params
     * @param  string $id
     * @return bool
     */
    public function receiveProduct(array $params, string $id): bool;

    /**
     * Cancel Purchase Order
     *
     * @param  string $id
     * @return bool
     */
    public function cancelPurchaseOrder(string $id): bool;

    /**
     * Put Product On Shelf
     *
     * @param  string $purchaseOrderId
     * @param  string $productId
     * @param  array $params
     * @return bool
     */
    public function putProductOnShelf(string $purchaseOrderId, string $productId,  array $params): bool;

    /**
     * Get Detail Purchase Product Shelve
     *
     * @param  string $purchaseOrderId
     * @param  string $productId
     * @return PurchaseOrder
     */
    public function getDetailPurchaseProductShelve(string $purchaseOrderId, string $productId): PurchaseOrder;

    /**
     * Finish Purchase Order
     *
     * @param  string $id
     * @return bool
     */
    public function finishPurchaseOrder(string $id): bool;

    /**
     * Get Activity Purchase Order
     *
     * @param  string $id
     * @return array
     */
    public function getActivityPurchaseOrder(string $id): array;

    /**
     * Get List Batch
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function getListBatch(DatatableParams $params): Paginator|LengthAwarePaginator;

    /**
     * Get Batch
     *
     * @param  string $id
     * @return PurchaseOrder
     */
    public function getDetailBatch(string $id): PurchaseOrder;

    /**
     * Get Receive Of Goods
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function getReceiptIn(DatatableParams $params): Paginator|LengthAwarePaginator;

    /**
     * getPurchaseOrderProduct
     *
     * @param  int $id
     * @return Collection
     */
    public function getPurchaseOrderProduct(int $id): Collection;

    /**
     * CreateCode
     *
     * @param  int $purchaseOrderId
     * @return string
     */
    public function createCode(int $purchaseOrderId): string;


    /**
     * Create Receipt Code
     *
     * @param  PurchaseOrder $purchaseOrder
     * @return string
     */
    public function createReceiptCode(PurchaseOrder $purchaseOrder): string;

    /**
     * Create Batch Code
     *
     * @param  int $purchaseOrderId
     * @return string
     */
    public function createBatchCode(int $purchaseOrderId): string;

    /**
     * Get Detail Purchase Order
     *
     * @param  string $id
     * @return array
     */
    public function getDetailPurchaseOrder(string $id): array;
}
