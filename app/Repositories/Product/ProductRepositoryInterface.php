<?php

namespace App\Repositories\Product;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{    
    /**
     * inventory
     *
     * @param  DatatableParams $params
     * @param  int $id
     * @return Paginator|LengthAwarePaginator
     */
    public function inventory(DatatableParams $params, int $id): Paginator|LengthAwarePaginator;
}
