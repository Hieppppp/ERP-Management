<?php

namespace App\Services\Shelve;

use App\Models\Shelve;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Shelve\ShelveRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ShelveService extends BaseService implements ShelveServiceInterface
{
    /**
     * ShelveRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }
    
    /**
     * getDetail
     *
     * @param  string $id
     * @return array
     */
    public function getDetail(string $id): array
    {
        return $this->repository->getDetail($id);
    }
    
    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model
    {
        $shelve = parent::create($params);
        $shelve->code = $this->createCode($shelve);
        $shelve->disableLogging()->save();
        return $shelve;
    }
     
    /**
     * createCode
     *
     * @param  Shelve $shelve
     * @return string
     */
    protected function createCode(Shelve $shelve): string
    {
        $warehouse_code = $shelve->warehouses->code;
        $shelveId = $shelve->id;
        return $warehouse_code . '-S' . str_pad($shelveId, STR_PAD_LEFT);
    }
}
