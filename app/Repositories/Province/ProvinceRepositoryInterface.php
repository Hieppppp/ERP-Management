<?php

namespace App\Repositories\Province;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProvinceRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
