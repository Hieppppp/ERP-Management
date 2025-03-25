<?php

namespace App\Repositories\City;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CityRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
