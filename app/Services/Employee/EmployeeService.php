<?php

namespace App\Services\Employee;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Employee\EmployeeRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class EmployeeService extends BaseService implements EmployeeServiceInterface
{
    /**
     * EmployeeRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }

    public function create(array $params): Model
    {
        $employee = parent::create($params);
        $employee->code = $this->createCode($employee->id);
        $employee->disableLogging()->save();
        return $employee;
    }

    protected function createCode(int $employeeId): string
    {
        return 'EMP' . str_pad($employeeId, 3, '0', STR_PAD_LEFT);
    }




}
