<?php

namespace App\Services\Supplier;

use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SupplierServiceInterface extends BaseServiceInterface
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
     * @param  string $id
     * @return array
     */
    public function getDetail(string $id): array;
}
