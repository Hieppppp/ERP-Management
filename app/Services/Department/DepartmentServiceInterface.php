<?php

namespace App\Services\Department;

use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DepartmentServiceInterface extends BaseServiceInterface
{
    public function search(array $params): LengthAwarePaginator;
}
