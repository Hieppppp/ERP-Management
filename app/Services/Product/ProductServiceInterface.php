<?php

namespace App\Services\Product;

use App\Common\Entity\DatatableParams;
use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

interface ProductServiceInterface extends BaseServiceInterface
{
    /**
     * Get Activity Product
     *
     * @param  string $id
     * @return array
     */
    public function getActivityProduct(string $id): array;

    /**
     * inventory
     *
     * @param  DatatableParams $params
     * @param  int $id
     * @return Paginator|LengthAwarePaginator
     */
    public function inventory(DatatableParams $params, int $id): Paginator|LengthAwarePaginator;
}
