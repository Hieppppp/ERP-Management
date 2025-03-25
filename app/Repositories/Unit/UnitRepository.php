<?php

namespace App\Repositories\Unit;

use App\Common\Entity\DatatableParams;
use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Models\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Unit::class);
    }

    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select([
            'units.*',
            'product_quantity'
        ])->leftJoinSub(
            Product::select('unit_id', DB::raw('COUNT(id) as product_quantity'))
                ->groupBy('unit_id'),
            'product_quantities',
            function ($join) {
                $join->on('units.id', '=', 'product_quantities.unit_id');
            }
        );;
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('units.id', 'like', "%{$params->search}%")
                    ->orWhere('units.name', 'like', "%{$params->search}%")
                    ->orWhere('units.symbol', 'like', "%{$params->search}%")
                    ->orWhere('units.description', 'like', "%{$params->search}%")
                    ->orWhere('units.created_at', 'like', "%{$params->search}%")
                    ->orWhere('product_quantity', 'like', "%{$params->search}%");
            });
        }

        if (!empty($params->searchColumns['id'])) {
            $query = $query->where('units.id', 'like', "%{$params->searchColumns['id']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('units.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['symbol'])) {
            $query = $query->where('units.symbol', 'like', "%{$params->searchColumns['symbol']}%");
        }

        if (!empty($params->searchColumns['product_quantity'])) {
            $query = $query->where('product_quantity', 'like', "%{$params->searchColumns['product_quantity']}%");
        }

        if (!empty($params->searchColumns['description'])) {
            $query = $query->where('units.description', 'like', "%{$params->searchColumns['description']}%");
        }

        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('units.created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }
        $query = $query->groupBy('units.id');
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'product_quantity') {
                $orderBy = 'product_quantity';
            }
            $query = $query->orderBy($orderBy, $orderType);
        }

        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $unit = $this->getModel()->select([
            "units.*",
            DB::raw("concat(name, '(', symbol, ')') as name")
        ]);
        if (!empty($params['search'])) {
            $unit = $unit->where(DB::raw("concat(name, '(', symbol, ')')"), 'like', "%{$params['search']}%");
        }
        return $unit->paginate();
    }
}
