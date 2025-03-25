<?php

namespace App\Repositories\PurchaseOrder;

use App\Common\Entity\DatatableParams;
use App\Models\PurchaseOrder;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

interface PurchaseOrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get Total By Status
     *
     * @return array
     */
    public function getTotalByStatus(): array;

    /**
     * Put Product On Shelf
     *
     * @param  string $purchaseOrderId
     * @param  string $productId
     * @param  array $params
     * @return bool
     */
    public function putProductOnShelf(string $purchaseOrderId, string $productId, array $params): bool;

    /**
     * Get Detail Purchase Product Shelve
     *
     * @param  string $purchaseOrderId
     * @param  string $productId
     * @return PurchaseOrder
     */
    public function getDetailPurchaseProductShelve(string $purchaseOrderId, string $productId): PurchaseOrder;

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
}
