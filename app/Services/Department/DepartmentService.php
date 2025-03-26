<?php

namespace App\Services\Department;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class DepartmentService extends BaseService implements DepartmentServiceInterface
{
    /**
     * DepartmentRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }
    
    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model
    {
        $department = parent::create($params);
        $department->code = $this->createCode($department->id);
        $department->disableLogging()->save();
        return $department;
    }
    
    /**
     * createCode
     *
     * @param  int $departmentId
     * @return string
     */
    protected function createCode(int $departmentId): string
    {
        return "DAP" . str_pad($departmentId, 3, '0', STR_PAD_LEFT);
    }
}
