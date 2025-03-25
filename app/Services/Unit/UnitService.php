<?php

namespace App\Services\Unit;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Unit\UnitRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UnitService extends BaseService implements UnitServiceInterface
{
    /**
     * UnitRepositoryInterface
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
