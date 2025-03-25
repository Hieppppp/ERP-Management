<?php

namespace App\Repositories;

use App\Common\Entity\DatatableParams;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

interface BaseRepositoryInterface
{
    /**
     * insert
     *
     * @param  array $data
     * @return void
     */
    public function insert(array $data);

    /**
     * Insert Multiple
     *
     * @param  array $data
     * @return void
     */
    public function insertMultiple(array $data);

    /**
     * delete
     *
     * @param  int $id
     * @return void
     */
    public function delete(int $id);

    /**
     * Find By Id
     *
     * @param  string|int $id
     * @param  array $columns
     * @return void
     */
    public function findById(string|int $id, array $columns = ['*']);

    /**
     * paginate
     *
     * @param DatatableParams $params
     * @return Paginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator;

    /**
     * Update
     *
     * @param  int $id
     * @param  array $data
     * @return void
     */
    public function update(int $id, array $data);

    /**
     * Order By
     *
     * @param  mixed $column
     * @param  string|null $value
     * @return void
     */
    public function orderBy(string $column, string|null $value);

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
