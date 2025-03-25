<?php

namespace App\Repositories\ReturnOrder;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;

interface ReturnOrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Receipt Out
     *
     * @param  DatatableParams $params
     * @return Paginator
     */
    public function receiptOut(DatatableParams $params): Paginator|LengthAwarePaginator;
}
