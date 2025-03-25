<?php

namespace App\Services\Country;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Country\CountryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CountryService extends BaseService implements CountryServiceInterface
{
    /**
     * CountryRepositoryInterface
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
