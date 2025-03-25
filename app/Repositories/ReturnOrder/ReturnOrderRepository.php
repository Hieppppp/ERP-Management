<?php

namespace App\Repositories\ReturnOrder;

use App\Common\Entity\DatatableParams;
use App\Enums\ReturnOrderStatusEnum;
use App\Models\ReturnOrder;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ReturnOrderRepository extends BaseRepository implements ReturnOrderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(ReturnOrder::class);
    }

    /**
     * Receipt Out
     *
     * @param  DatatableParams $params
     * @return Paginator
     */
    public function receiptOut(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select([
            'return_orders.id',
            'return_orders.created_at',
            'return_orders.status',
            'suppliers.name as supplier_name',
            DB::raw('CONCAT(suppliers.detail_address, ", ", suppliers.city, ", ", suppliers.province, ", ", suppliers.country) as shipping_address'),
            "return_orders.code as code",
            DB::raw('sum(return_order_details.demand_quantity) as quantity_demand'),
            DB::raw(sprintf("(case
                when return_orders.status = '%s' then sum(return_order_details.quantity)
                else null
            end)
            as quantity_returned", ReturnOrderStatusEnum::DONE))
        ])
            ->join('purchase_orders', 'purchase_orders.id', 'return_orders.purchase_order_id')
            ->join('suppliers', 'suppliers.id', 'purchase_orders.supplier_id')
            ->leftJoin('return_order_details', 'return_order_details.return_order_id', 'return_orders.id')
            ->groupBy('return_orders.id');
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('return_orders.code', 'like', "%{$params->search}%")
                    ->orWhere('suppliers.name', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw('CONCAT(suppliers.detail_address, ", ", suppliers.city, ", ", suppliers.province, ", ", suppliers.country)'), 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('return_orders.code', 'like', "%{$params->searchColumns['code']}%");
        }
        if (!empty($params->searchColumns['shipping_address'])) {
            $query = $query->where(DB::raw('CONCAT(suppliers.detail_address, ", ", suppliers.city, ", ", suppliers.province, ", ", suppliers.country)'), 'like', "%{$params->searchColumns['shipping_address']}%");
        }
        if (!empty($params->searchColumns['supplier_name'])) {
            $query = $query->where('suppliers.name', 'like', "%{$params->searchColumns['supplier_name']}%");
        }
        if (!empty($params->searchColumns['status'])) {
            $query = $query->where('return_orders.status', $params->searchColumns['status']);
        }
        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            if ($orderBy == 'code') {
                $orderBy = 'return_orders.code';
            }
            if ($orderBy == 'created_at') {
                $orderBy = 'return_orders.created_at';
            }
            if ($orderBy == 'supplier_name') {
                $orderBy = 'suppliers.name';
            }
            if ($orderBy == 'shipping_address') {
                $orderBy = DB::raw('CONCAT(suppliers.detail_address, ", ", suppliers.city, ", ", suppliers.province, ", ", suppliers.country)');
            }
            if ($orderBy == 'quantity_demand') {
                $orderBy = DB::raw('sum(return_order_details.demand_quantity)');
            }
            if ($orderBy == 'quantity_received') {
                $orderBy = DB::raw('sum(return_order_details.quantity)');
            }
            $query = $query->orderBy($orderBy, $orderType);
        }
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }
}
