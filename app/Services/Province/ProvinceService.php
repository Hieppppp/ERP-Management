<?php

namespace App\Services\Province;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Province\ProvinceRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProvinceService extends BaseService implements ProvinceServiceInterface
{
    /**
     * @param ProvinceRepository $repository
     *
     * @return void
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
