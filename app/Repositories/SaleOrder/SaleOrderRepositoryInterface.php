<?php

namespace App\Repositories\SaleOrder;

use App\Common\Entity\DatatableParams;
use App\Models\RegisterPayment;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface SaleOrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get Detail
     *
     * @param  string $invoice
     * @return array
     */
    public function getDetail(string $invoice): array;

    /**
     * Register Payment
     *
     * @param  array $params
     * @param  string $id
     * @return RegisterPayment
     */
    public function registerPayment(array $params, string $id): RegisterPayment;

    /**
     * Get Sale Order Details
     *
     * @param  int $saleOrderId
     * @return Collection
     */
    public function getSaleOrderDetails(int $saleOrderId): Collection;

    /**
     * Get Invoice List
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function getInvoiceList(DatatableParams $params): Paginator|LengthAwarePaginator;
}
