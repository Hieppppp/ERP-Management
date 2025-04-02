<?php

namespace App\Repositories\Position;

use App\Common\Entity\DatatableParams;
use App\Models\Employee;
use App\Repositories\BaseRepository;
use App\Models\Position;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class PositionRepository extends BaseRepository implements PositionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Position::class);
    }

    public function paginate(DatatableParams $params): LengthAwarePaginator|Paginator
    {
        // Truy vấn đếm số lượng nhân viên theo position_id
        $employeeCounts = Employee::select('position_id', DB::raw('COUNT(id) as employee_count'))
            ->groupBy('position_id');
        // Truy vấn chính với LEFT JOIN subquery
        $query = $this->getModel()
            ->leftJoinSub($employeeCounts, 'employee_counts', function ($join) {
                $join->on('positions.id', '=', 'employee_counts.position_id');
            })
            ->select([
                'positions.*',
                'employee_counts.employee_count as employee_quantity'
            ]);

        // Tìm kiếm chung
        if ($params->search) {
            $query->where(function ($query) use ($params) {
                $query->where('positions.code', 'like', "%{$params->search}%")
                    ->orWhere('positions.name', 'like', "%{$params->search}%")
                    ->orWhere('positions.description', 'like', "%{$params->search}%")
                    ->orWhere('employee_counts.employee_count', 'like', "%{$params->search}%");
            });
        }

        // Tìm kiếm theo từng cột cụ thể
        if (!empty($params->searchColumns['code'])) {
            $query->where('positions.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query->where('positions.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['description'])) {
            $query->where('positions.description', 'like', "%{$params->searchColumns['description']}%");
        }
        if (!empty($params->searchColumns['employee_quantity'])) {
            $query = $query->where('employee_counts.employee_count', 'like', "%{$params->searchColumns['employee_quantity']}%");
        }
        $query = $query->groupBy('positions.id');

        // Xử lý sắp xếp
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'employee_quantity') {
                $orderBy = 'employee_counts.employee_count';
            }
            $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }


    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $position = $this->getModel()->select(["*"]);

        if (!empty($params['search'])) {
            $position = $position->where('name', 'like', "%{$params['search']}%");
        }
        return $position->paginate();
    }
}
