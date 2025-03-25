<?php

namespace App\Repositories\SaleOrder;

use App\Models\RegisterPayment;
use App\Models\SaleOrder;
use App\Repositories\BaseRepository;
use App\Common\Entity\DatatableParams;
use App\Enums\InvoiceStatusEnum;
use App\Enums\SaleOrderStatusEnum;
use App\Models\Product;
use App\Models\ProductLocation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class SaleOrderRepository extends BaseRepository implements SaleOrderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(SaleOrder::class);
    }

    /**
     * Get Detail
     *
     * @param  string $invoice
     * @return array
     */
    public function getDetail(string $invoice): array
    {
        $saleOrder = parent::findById($invoice);

        return $saleOrder->toArray();
    }

    /**
     * Register Payment
     *
     * @param  array $params
     * @param  string $id
     * @return RegisterPayment
     */
    public function registerPayment(array $params, string $id): RegisterPayment
    {
        $params['sale_order_id'] = $id;
        return RegisterPayment::create($params);
    }

    /**
     * paginate
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select(
            [
                'sale_orders.*',
                DB::raw("concat(c.first_name, ' ', c.last_name) as customer_name"),
                DB::raw("sum(sod.quantity) as total_quantity"),
                DB::raw("ROUND(SUM(sod.quantity * sod.unit_price * (100 - sod.discount_rate) / 100) * (1 + sale_orders.tax_rate / 100), 2) as total_amount")
            ]
        )
            ->join('customers as c', 'c.id', 'sale_orders.customer_id')
            ->leftJoin('sale_order_details as sod', 'sod.sale_order_id', 'sale_orders.id');
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('sale_orders.code', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw("concat(c.first_name, ' ', c.last_name)"), 'like', "%{$params->search}%")
                    ->orWhere('sale_orders.created_at', 'like', "%{$params->search}%")
                    ->orWhere('sale_orders.deliver_address', 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('sale_orders.code', 'like', "%{$params->searchColumns['code']}%");
        }
        if (!empty($params->searchColumns['customer_name'])) {
            $query = $query->where(DB::raw("concat(c.first_name, ' ', c.last_name)"), 'like', "%{$params->searchColumns['customer_name']}%");
        }
        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('sale_orders.created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }
        if (!empty($params->searchColumns['deliver_address'])) {
            $query = $query->where('sale_orders.deliver_address', 'like', "%{$params->searchColumns['deliver_address']}%");
        }
        if (!empty($params->searchColumns['order_status'])) {
            $query = $query->where('sale_orders.order_status', $params->searchColumns['order_status']);
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }
        $query = $query->groupBy('sale_orders.id');
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }

    /**
     * getSaleOrderDetails
     *
     * @param  int $saleOrderId
     * @return void
     */
    public function getSaleOrderDetails(int $saleOrderId): Collection
    {
        $saleOrderDetails = Product::with(['images'])
            ->withTrashed()
            ->select([
                'products.*',
                'ct.name as category_name',
                DB::raw("concat(u.name, '(', u.symbol, ')') as unit_name"),
                DB::raw("COALESCE(SUM(pl.quantity), 0) as quantity"),
                'sod.quantity as order_quantity',
                'sod.unit_price as order_unit_price',
                'sod.discount_rate as discount_rate',
                DB::raw('IF(COUNT(sol.id) > 0, 1, 0) as stock_validated')
            ])->join('sale_order_details as sod', function ($join) use ($saleOrderId) {
                $join->on('sod.product_id', 'products.id')
                    ->where('sod.sale_order_id', $saleOrderId);
            })->leftJoin('categories as ct', 'ct.id', 'products.category_id')
            ->leftJoin('units as u', 'u.id', 'products.unit_id')
            ->leftJoin('sale_order_locations as sol', 'sod.id', 'sol.sale_order_detail_id')
            ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')->groupBy('products.id')->get();
        return $saleOrderDetails;
    }

    /**
     * Get Invoice List
     *
     * @param  DatatableParams $params
     * @return Paginator|LengthAwarePaginator
     */
    public function getInvoiceList(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select(
            [
                'sale_orders.*',
                DB::raw("concat(c.first_name, ' ', c.last_name) as customer_name"),
                DB::raw("sum(sod.quantity) as total_quantity"),
                DB::raw("ROUND(SUM(sod.quantity * sod.unit_price * (100 - sod.discount_rate) / 100) * (1 + sale_orders.tax_rate / 100), 2) as total_amount"),
                DB::raw("COALESCE(sum(rp.paid_amount), 0) as total_paid_amount"),
            ]
        )
            ->join('customers as c', 'c.id', 'sale_orders.customer_id')
            ->leftJoin('sale_order_details as sod', 'sod.sale_order_id', 'sale_orders.id')
            ->leftJoin('register_payments as rp', 'rp.sale_order_id', 'sale_orders.id')
            ->whereNot('sale_orders.order_status', SaleOrderStatusEnum::DRAFT);
        if ($params->search) {
            $query = $query->where(function ($query) use ($params) {
                $query->where('sale_orders.invoice_code', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw("concat(c.first_name, ' ', c.last_name)"), 'like', "%{$params->search}%")
                    ->orWhere('sale_orders.created_at', 'like', "%{$params->search}%");
            });
        }
        if (!empty($params->searchColumns['code'])) {
            $query = $query->where('sale_orders.invoice_code', 'like', "%{$params->searchColumns['code']}%");
        }
        if (!empty($params->searchColumns['customer_name'])) {
            $query = $query->where(DB::raw("concat(c.first_name, ' ', c.last_name)"), 'like', "%{$params->searchColumns['customer_name']}%");
        }
        if (!empty($params->searchColumns['created_at'])) {
            $query = $query->where('sale_orders.created_at', 'like', "%{$params->searchColumns['created_at']}%");
        }

        if (!empty($params->searchColumns['payment_status'])) {
            if ($params->searchColumns['payment_status'] == InvoiceStatusEnum::UNPAID) {
                $query = $query->havingRaw('total_paid_amount <= 0 and total_amount > 0');
            } elseif ($params->searchColumns['payment_status'] == InvoiceStatusEnum::PAID) {
                $query = $query->havingRaw('total_paid_amount >= total_amount');
            } elseif ($params->searchColumns['payment_status'] == InvoiceStatusEnum::PARTIALLY_PAID) {
                $query = $query->havingRaw('total_amount > total_paid_amount and total_paid_amount > 0');
            }
        }

        if ($params->order) {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];
            $query = $query->orderBy($orderBy, $orderType);
        }
        $query = $query->groupBy('sale_orders.id');
        return $query->paginate($params->length, $params->columns, 'page', $params->page);
    }
}
