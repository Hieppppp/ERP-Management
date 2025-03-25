<?php

namespace App\Services\Category;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService extends BaseService implements CategoryServiceInterface
{
    /**
     * CategoryRepositoryInterface
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
