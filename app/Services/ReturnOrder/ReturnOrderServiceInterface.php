<?php

namespace App\Services\ReturnOrder;

use App\Common\Entity\DatatableParams;
use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface ReturnOrderServiceInterface extends BaseServiceInterface
{
    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model;

    /**
     * update
     *
     * @param  array $params
     * @param  int $id
     * @return Model
     */
    public function update(array $params, int $id): Model;

    /**
     * Receipt Out
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function receiptOut(DatatableParams $params): Paginator|LengthAwarePaginator;
}
