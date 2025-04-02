<?php

namespace App\Repositories\Position;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PositionRepositoryInterface extends BaseRepositoryInterface
{
    public function search(array $params): LengthAwarePaginator;

}
