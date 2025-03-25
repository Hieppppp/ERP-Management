<?php

namespace App\Services\City;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\City\CityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CityService extends BaseService implements CityServiceInterface
{
    /**
     *@param CityRepository $repository
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        return $this->repository->search($params);
    }
}
