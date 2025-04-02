<?php

namespace App\Repositories\Employee;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Employee::class);
    }

    public function paginate(DatatableParams $params): LengthAwarePaginator|Paginator
    {
        $query = $this->getModel()->select([
            'employees.*',
            'dp.name as department_name',
            'ps.name as position_name'
        ]);

        $query->leftJoin('departments as dp', 'dp.id', 'employees.department_id')
            ->leftJoin('positions as ps', 'ps.id', 'employees.position_id')
            ->where('employees.deleted_at', null);

        if ($params->search) {
            $query = $query->where( function($query) use ($params) {
                $query->where('employees.code', 'like', "%{$params->search}%")
                    ->orWhere('dp.name', 'like', "%{$params->search}%")
                    ->orWhere('ps.name'. 'like', "%{$params->search}%")
                    ->orWhere('employees.email', 'like', "%{$params->search}%")
                    ->orWhere('employees.phone', 'like', "%{$params->search}%");
                    
            });
        }

        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('employees.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['department_name'])) {
            $query = $query->where('dp.name', 'like', "%{$params->searchColumns['department_name']}%");
        }

        if (!empty($params->searchColumns['position_name'])) {
            $query = $query->where('ps.name', 'like', "%{$params->searchColumns['position_name']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('employees.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['email'])) {
            $query = $query->where('employees.email', 'like', "%{$params->searchColumns['email']}%");
        }

        if (!empty($params->searchColumns['phone'])) {
            $query = $query->where('employees.phone', 'like', "%{$params->searchColumns['phone']}%");
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }
}
