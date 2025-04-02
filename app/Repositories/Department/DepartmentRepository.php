<?php

namespace App\Repositories\Department;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\DB;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Department::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): PaginationPaginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select([
            'departments.*',
            'employee_count as employee_quantity'
        ])
            ->leftJoinSub(
                Employee::select('department_id', DB::raw('COUNT(id) as employee_count'))
                ->groupBy('department_id'),
                'employee_counts',
                function ($join) {
                    $join->on('department_id', '=', 'employee_counts.department_id');
                }
            );

        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('departments.code', 'like', "%{$params->search}%")
                    ->orWhere('departments.name', 'like', "%{$params->search}%")
                    ->orWhere('departments.description', 'like', "%{$params->search}%")
                    ->orWhere('employee_counts.employee_count', 'like', "%{$params->search}%");

            });
        }

        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('departments.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('departments.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['description'])) {
            $query = $query->where('departments.description', 'like', "%{$params->searchColumns['description']}%");
        }

        if (!empty($params->searchColumns['employee_quantity'])) {
            $query = $query->having('employee_counts.employee_count', 'like', "%{$params->searchColumns['employee_quantity']}%");
        }
        $query = $query->groupBy('departments.id');
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'employee_quantity') {
                $orderBy = 'employee_counts.employee_count';
            }
            $query = $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    public function search(array $params): LengthAwarePaginator
    {
        $department = $this->getModel()->select(["*"]);
        if (!empty($params['search'])) {
            $department = $department->where('name', 'like', "%{$params['search']}%");
        }
        return $department->paginate();
    }

    
}
