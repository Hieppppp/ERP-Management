<?php

namespace App\Repositories\Department;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DepartmentRepositoryInterface extends BaseRepositoryInterface
{
    public function search(array $params): LengthAwarePaginator;
}
