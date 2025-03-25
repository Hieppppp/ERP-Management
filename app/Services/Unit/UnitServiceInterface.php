<?php

namespace App\Services\Unit;

use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UnitServiceInterface extends BaseServiceInterface
{
    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
