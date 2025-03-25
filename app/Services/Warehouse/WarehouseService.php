<?php

namespace App\Services\Warehouse;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Warehouse\WarehouseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class WarehouseService extends BaseService implements WarehouseServiceInterface
{
    /**
     * WarehouseRepositoryInterface
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

    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model
    {
        $warehouse = parent::create($params);
        $warehouse->code = $this->createCode($warehouse->id);
        $warehouse->disableLogging()->save();
        return $warehouse;
    }

    /**
     * CreateCode
     *
     * @param  int $warehouseId
     * @return string
     */
    protected function createCode(int $warehouseId): string
    {
        return 'WH' . str_pad($warehouseId, STR_PAD_LEFT);
    }

    /**
     * Get Detail
     *
     * @param  int $id
     * @return Model
     */
    public function getDetail(int $id): Model
    {
        return $this->repository->getDetail($id);
    }
}