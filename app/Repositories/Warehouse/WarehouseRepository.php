<?php

namespace App\Repositories\Warehouse;

use App\Common\Entity\DatatableParams;
use App\Models\Shelve;
use App\Repositories\BaseRepository;
use App\Models\Warehouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class WarehouseRepository extends BaseRepository implements WarehouseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Warehouse::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()
            ->select([
                'warehouses.*',
                DB::raw("concat(warehouses.detail_address, ', ',  warehouses.city, ', ', warehouses.province, ', ', warehouses.country) as address"),
                DB::raw("COALESCE(count(shelves.id), 0) as shelves_count")
            ])->leftJoin('shelves', 'shelves.warehouse_id', 'warehouses.id')
            ->where('shelves.deleted_at', null);
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('warehouses.code', 'like', "%{$params->search}%")
                    ->orWhere('warehouses.name', 'like', "%{$params->search}%")
                    ->orWhere('warehouses.postal_code', 'like', "%{$params->search}%")
                    ->orWhere('warehouses.contact', 'like', "%{$params->search}%")
                    ->orWhere('warehouses.updated_at', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw("concat(warehouses.detail_address, ', ',  warehouses.city, ', ', warehouses.province, ', ', warehouses.country)"), 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('warehouses.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('warehouses.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['postal_code'])) {
            $query = $query->where('warehouses.postal_code', 'like', "%{$params->searchColumns['postal_code']}%");
        }

        if (!empty($params->searchColumns['address'])) {
            $query = $query->where(DB::raw("concat(warehouses.detail_address, ', ',  warehouses.city, ', ', warehouses.province, ', ', warehouses.country)"), 'like', "%{$params->searchColumns['address']}%");
        }

        if (!empty($params->searchColumns['contact'])) {
            $query = $query->where('warehouses.contact', 'like', "%{$params->searchColumns['contact']}%");
        }

        if (!empty($params->searchColumns['updated_at'])) {
            $query = $query->where('warehouses.updated_at', 'like', "%{$params->searchColumns['updated_at']}%");
        }
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'address') {
                $orderBy = DB::raw("concat(warehouses.detail_address, ', ',  warehouses.city, ', ', warehouses.province, ', ', warehouses.country)");
            }
            if ($orderBy == 'code') {
                $orderBy = 'warehouses.code';
            }
            if ($orderBy == 'name') {
                $orderBy = 'warehouses.name';
            }
            if ($orderBy == 'postal_code') {
                $orderBy = 'warehouses.postal_code';
            }
            if ($orderBy == 'updated_at') {
                $orderBy = 'warehouses.updated_at';
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        $query = $query->groupBy('warehouses.id');
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
        $shelves = $this->getModel()->select('*');
        if (!empty($params['search'])) {
            $shelves = $shelves->where('name', 'like', "%{$params['search']}%");
        }
        return $shelves->paginate();
    }

    /**
     * Get Detail
     *
     * @param  int $id
     * @return Model
     */
    public function getDetail(int $id): Model
    {
        $warehouse = $this->findById($id);
        $shelves = Shelve::select('shelves.*', DB::raw("COALESCE(sum(plq.quantity), 0) as quantity"))
            ->leftJoin('product_locations as plq', 'plq.shelve_id', 'shelves.id')
            ->where('warehouse_id', $id)
            ->groupBy('shelves.id')
            ->get();
        $warehouse->shelves = $shelves;
        return $warehouse;
    }
}
