<?php

namespace App\Repositories\Unit;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UnitRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
