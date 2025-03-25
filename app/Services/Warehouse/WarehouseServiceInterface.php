<?php

namespace App\Services\Warehouse;

use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface WarehouseServiceInterface extends BaseServiceInterface
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
