<?php

namespace App\Services\Tax;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Tax\TaxRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaxService extends BaseService implements TaxServiceInterface
{
    /**
     * TaxRepositoryInterface
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
