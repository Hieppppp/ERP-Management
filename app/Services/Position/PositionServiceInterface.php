<?php

namespace App\Services\Position;

use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PositionServiceInterface extends BaseServiceInterface
{
    public function search(array $params): LengthAwarePaginator;
}
