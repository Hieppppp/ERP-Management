<?php

namespace App\Services;

use App\Common\Entity\DatatableParams;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

interface BaseServiceInterface
{
    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator;

    /**
     * delete
     *
     * @param  int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null;

    /**
     * create
     *
     * @param  int $id
     * @return bool|null
     */
    public function create(array $params): Model;

    /**
     * Find By Id
     *
     * @param  string|int $id
     * @return Model
     */
    public function findById(string|int $id): Model;

    /**
     * update
     *
     * @param  array $params
     * @param  int $id
     * @return Model
     */
    public function update(array $params, int $id): Model;

    /**
     * with
     *
     * @param  array $relations
     * @return Builder
     */
    public function with(array $relations): Builder;

    /**
     * Get All
     *
     * @param  array $params
     * @return Collection
     */
    public function getAll(array $params = ['*']): Collection;
}
