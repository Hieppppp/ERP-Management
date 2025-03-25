<?php

namespace App\Repositories\Warehouse;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface WarehouseRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;

    /**
     * Get Detail
     *
     * @param  int $id
     * @return Model
     */
    public function getDetail(int $id): Model;
}
