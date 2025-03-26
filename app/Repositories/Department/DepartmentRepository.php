<?php

namespace App\Repositories\Department;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator as PaginationPaginator;

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
        ]);

        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('departments.code', 'like', "%{$params->search}%")
                    ->orWhere('departments.name', 'like', "%{$params->search}%")
                    ->orWhere('departments.description', 'like', "%{$params->search}%");
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

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    
}
