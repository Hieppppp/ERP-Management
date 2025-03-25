<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
