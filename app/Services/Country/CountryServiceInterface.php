<?php

namespace App\Services\Country;

use App\Services\BaseServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CountryServiceInterface extends BaseServiceInterface
{
    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
