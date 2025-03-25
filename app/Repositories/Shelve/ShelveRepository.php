<?php

namespace App\Repositories\Shelve;

use App\Common\Entity\DatatableParams;
use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Models\Shelve;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ShelveRepository extends BaseRepository implements ShelveRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Shelve::class);
    }

    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()
            ->select([
                'shelves.*',
                'w.name as warehouse',
                DB::raw('COALESCE(SUM(product_locations.quantity), 0) as quantity')
            ])
            ->join('warehouses as w', 'shelves.warehouse_id', '=', 'w.id')
            ->leftJoin('product_locations', 'shelves.id', '=', 'product_locations.shelve_id');
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('shelves.code', 'like', "%{$params->search}%")
                    ->orWhere('shelves.name', 'like', "%{$params->search}%")
                    ->orWhere('shelves.location', 'like', "%{$params->search}%")
                    ->orWhere('shelves.updated_at', 'like', "%{$params->search}%")
                    ->orWhere('w.name', 'like', "%{$params->search}%");
            });
        }

        if (!empty($params->params['warehouseId'])) {
            $query = $query->where('w.id', $params->params['warehouseId']);
        }

        if (!empty($params->params['excludeShelve'])) {
            $query = $query->whereNotIn('shelves.id', explode(',', $params->params['excludeShelve']));
        }

        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('shelves.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('shelves.name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['location'])) {
            $query = $query->where('shelves.location', 'like', "%{$params->searchColumns['location']}%");
        }

        if (!empty($params->searchColumns['warehouse'])) {
            $query = $query->where('w.name', 'like', "%{$params->searchColumns['warehouse']}%");
        }

        if (!empty($params->searchColumns['updated_at'])) {
            $query = $query->where('shelves.updated_at', 'like', "%{$params->searchColumns['updated_at']}%");
        }
        $query = $query->groupBy('shelves.id');
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'code') {
                $orderBy = 'shelves.code';
            }
            if ($orderBy == 'updated_at') {
                $orderBy = 'shelves.updated_at';
            }
            if ($orderBy == 'quantity') {
                $orderBy = DB::raw('COALESCE(SUM(product_locations.quantity), 0)');
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }

    /**
     * Get Detail
     *
     * @param  string $id
     * @return array
     */
    public function getDetail(string $id): array
    {
        $shelve = $this->with(['warehouses'])->findOrFail($id);
        $products = Product::withTrashed()->with(['category', 'unit', 'images'])
            ->join('product_locations', 'products.id', '=', 'product_locations.product_id')
            ->join('purchase_orders', 'product_locations.purchase_order_id', '=', 'purchase_orders.id')
            ->where('product_locations.shelve_id', $id)
            ->select('products.*', 'purchase_orders.id as purchase_order_id', 'product_locations.quantity as total_quantity', 'purchase_orders.batch_code', 'purchase_orders.received_date')
            ->where('product_locations.quantity', '>', 0)
            ->get();
        return ['shelve' => $shelve, 'products' => $products];
    }
}
