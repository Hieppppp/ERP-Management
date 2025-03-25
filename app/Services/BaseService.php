<?php

namespace App\Services;

use App\Common\Entity\DatatableParams;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class BaseService implements BaseServiceInterface
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        public BaseRepository $repository
    ) {
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        return $this->repository->paginate($params);
    }

    /**
     * Get All
     *
     * @param  array $params
     * @return Collection
     */
    public function getAll(array $params = ['*']): Collection
    {
        return $this->repository->getAll($params);
    }

    /**
     * delete
     *
     * @param  int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null
    {
        return $this->repository->delete($id);
    }

    /**
     * create
     *
     * @param  int $id
     * @return Model
     */
    public function create(array $params): Model
    {
        return $this->repository->insert($params);
    }

    /**
     * Find By Id
     *
     * @param  string|int $id
     * @return Model
     */
    public function findById(string|int $id): Model
    {
        return $this->repository->findById($id);
    }

    /**
     * update
     *
     * @param  array $params
     * @param  int $id
     * @return Model
     */
    public function update(array $params, int $id): Model
    {
        return $this->repository->update($id, $params);
    }

    /**
     * with
     *
     * @param  array $relations
     * @return Builder
     */
    public function with(array $relations): Builder
    {
        return $this->repository->with($relations);
    }
}
