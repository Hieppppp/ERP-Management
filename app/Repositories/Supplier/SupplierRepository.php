<?php

namespace App\Repositories\Supplier;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Supplier::class);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()
            ->select([
                'suppliers.*',
                DB::raw("concat(detail_address, ', ',  city, ', ', province, ', ', country) as address"),
                DB::raw("COALESCE(SUM(pl.quantity), 0) as quantity")
            ])
            ->leftJoin('purchase_orders', 'suppliers.id', '=', 'purchase_orders.supplier_id')
            ->leftJoin('product_locations as pl', 'purchase_orders.id', '=', 'pl.purchase_order_id');
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('suppliers.code', 'like', "%{$params->search}%")
                    ->orWhere('name', 'like', "%{$params->search}%")
                    ->orWhere('email', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw("concat(detail_address, ', ',  city, ', ', province, ', ', country)"), 'like', "%{$params->search}%")
                    ->orWhere('phone', 'like', "%{$params->search}%");
            });
        }

        if (!empty($params->params['includeProductIds'])) {
            $includeProductIds = explode(',', $params->params['includeProductIds']);
            $query = $query->whereHas('products', function ($query) use ($includeProductIds) {
                $query->whereIn('id', $includeProductIds);
            });
        }

        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('suppliers.code', 'like', "%{$params->searchColumns['code']}%");
        }

        if (!empty($params->searchColumns['name'])) {
            $query = $query->where('name', 'like', "%{$params->searchColumns['name']}%");
        }

        if (!empty($params->searchColumns['email'])) {
            $query = $query->where('email', 'like', "%{$params->searchColumns['email']}%");
        }

        if (!empty($params->searchColumns['address'])) {
            $query = $query->where(DB::raw("concat(detail_address, ', ',  city, ', ', province, ', ', country)"), 'like', "%{$params->searchColumns['address']}%");
        }

        if (!empty($params->searchColumns['phone'])) {
            $query = $query->where('phone', 'like', "%{$params->searchColumns['phone']}%");
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'code') {
                $orderBy = 'suppliers.code';
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        $query = $query->groupBy('suppliers.id');

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
        $supplier = $this->getModel()->select(["*"]);
        if (!empty($params['search'])) {
            $supplier = $supplier->where('name', 'like', "%{$params['search']}%");
        }
        return $supplier->paginate();
    }
}
