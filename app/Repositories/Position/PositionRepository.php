<?php

namespace App\Repositories\Position;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Position;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PositionRepository extends BaseRepository implements PositionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Position::class);
    }

    public function paginate(DatatableParams $params): LengthAwarePaginator|Paginator
    {
        $query = $this->getModel()
            ->select([
                'positions.*',
                
            ]);
           
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('positions.code', 'like', "%{$params->search}%")
                    ->orWhere('positions.name', 'like', "%{$params->search}%")
                    ->orWhere('positions.description', 'like', "%{$params->search}%");
                    
            });
        }


        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('suppliers.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('positions.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['description'])) {
            $query = $query->where('positions.description', 'like', "%{$params->searchColumns['description']}%");
        }


        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }
        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }
}
