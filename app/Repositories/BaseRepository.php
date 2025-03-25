<?php

namespace App\Repositories;

use App\Common\Entity\DatatableParams;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class BaseRepository implements BaseRepositoryInterface
{

    /**
     * The query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected $whereIns = [];

    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected $whereLike = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     *
     * @var array
     */
    protected $orderBys = [];

    /**
     * __construct
     *
     * @param string $model
     * @return void
     */
    public function __construct(
        public string $model
    ) {
    }

    public function getModel(): Model
    {
        return new $this->model;
    }

    /**
     * insert
     *
     * @param  array $data
     * @return Model
     */
    public function insert(array $data): Model
    {
        return $this->getModel()->create($data);
    }

    /**
     * Insert Multiple
     *
     * @param  array $data
     * @return void
     */
    public function insertMultiple(array $data): Collection
    {
        $models = new Collection();

        foreach ($data as $d) {
            $models->push($this->insert($d));
        }

        return $models;
    }

    /**
     * delete
     *
     * @param  int $id
     * @return bool|null
     *
     * @throws \LogicException
     */
    public function delete(int $id): bool|null
    {
        return $this->findById($id)->delete();
    }

    /**
     * Find By Id
     *
     * @param  string|int $id
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function findById(string|int $id, array $columns = ['*']): Collection|Model
    {
        return $this->getModel()->findOrFail($id, $columns);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select($params->columns);
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                foreach ($params->columns as $key => $column) {
                    if ($column) {
                        if ($key == 0) {
                            $query = $query->where($column, 'like', "%{$params->search}%");
                        } else {
                            $query = $query->orWhere($column, 'like', "%{$params->search}%");
                        }
                    }
                }
            });
        }
        foreach ($params->columns as $key => $column) {
            if (!empty($params->searchColumns[$column])) {
                $query = $query->where($column, 'like', "%{$params->searchColumns[$column]}%");
            }
        }
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    /**
     * Update
     *
     * @param  int $id
     * @param  array $data
     * @return Model
     */
    public function update(int $id, array $data): Model
    {
        $model = $this->getModel()->findOrFail($id);

        $model->update($data);

        return $model;
    }

    // public function where();

    /**
     * Order By
     *
     * @param  mixed $column
     * @param  string|null $value
     * @return $this
     */
    public function orderBy(string $column, string|null $value = 'asc')
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses()
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        return $this;
    }

    /**
     * with
     *
     * @param  array $relations
     * @return Builder
     */
    public function with(array $relations): Builder
    {
        return $this->getModel()->with($relations);
    }

    /**
     * Get All
     *
     * @param  array $columns
     * @return Collection
     */
    public function getAll(array $columns = ['*']): Collection
    {
        return $this->getModel()->get($columns);
    }
}
