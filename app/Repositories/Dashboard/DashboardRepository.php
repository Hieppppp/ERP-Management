<?php

namespace App\Repositories\Dashboard;

use App\Enums\SaleOrderStatusEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLocation;
use App\Models\PurchaseOrder;
use App\Models\SaleOrder;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Current Stock
     *
     * @return array
     */
    public function currentStock(): array
    {
        $query = Product::select([
            'products.*',
            'u.symbol',
            DB::raw("sum(pl.quantity) as quantity")
        ]);
        $query = $query
            ->leftJoin('units as u', 'u.id', 'products.unit_id')
            ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')
            ->orderBy(DB::raw("sum(pl.quantity)"), 'desc')
            ->groupBy('products.id')
            ->limit(5);
        return $query->get()->toArray();
    }

    /**
     * Low Stock
     *
     * @return array
     */
    public function lowStock(): array
    {
        $query = Product::select([
            'products.*',
            'u.symbol',
            DB::raw("COALESCE(sum(pl.quantity), 0) as quantity")
        ]);
        $query = $query
            ->leftJoin('units as u', 'u.id', 'products.unit_id')
            ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')
            ->groupBy('products.id')
            ->havingRaw("COALESCE(sum(pl.quantity), 0) <= products.min_quantity")
            ->orderBy(DB::raw("COALESCE(sum(pl.quantity), 0)"), 'asc')
            ->limit(5);
        return $query->get()->toArray();
    }

    /**
     * Statistic
     *
     * @return array
     */
    public function statistic(): array
    {
        //get total purchase order
        $totalPurchaseOrder = PurchaseOrder::count();
        //get total sales order
        $totalSalesOrder = SaleOrder::count();
        //get total inventory value
        $totalInventoryValue = Product::select([
            DB::raw("sum(products.unit_price * pl.quantity) as total_inventory_value")
        ])
            ->join('product_locations as pl', 'pl.product_id', 'products.id')
            ->where('products.deleted_at', null)
            ->value('total_inventory_value');
        //get average inventory value
        $averageInventoryValue = ProductLocation::select([
            DB::raw("sum(product_locations.unit_cost * product_locations.quantity)/sum(product_locations.quantity) as average_inventory_value")
        ])
            ->value('average_inventory_value');
        return [
            'total_purchase_order' => $totalPurchaseOrder,
            'total_sales_order' => $totalSalesOrder,
            'total_inventory_value' => $totalInventoryValue,
            'average_inventory_value' => $averageInventoryValue
        ];
    }

    /**
     * Recent Orders
     *
     * @param array | null $params
     * @return array
     */
    public function recentOrders(array | null $params): array
    {
        $query = SaleOrder::select([
            'sale_orders.*',
            DB::raw("concat(c.first_name, ' ', c.last_name) as customer_name"),
            DB::raw("sum(sod.quantity) as total_quantity"),
        ])
            ->leftJoin('customers as c', 'c.id', 'sale_orders.customer_id')
            ->leftJoin('sale_order_details as sod', 'sod.sale_order_id', 'sale_orders.id');
        if (!empty($params['status'])) {
            $query = $query->whereIn('sale_orders.order_status', $params['status']);
        }
        $query = $query
            ->orderBy('sale_orders.created_at', 'desc')
            ->groupBy('sale_orders.id')
            ->limit(5);
        return $query->get()->toArray();
    }

    /**
     * topSelling
     *
     * @param array $name
     * @return array
     */
    public function topSelling(array $param): array
    {
        $products = Product::select([
            'products.*',
            'units.symbol',
            DB::raw("sum(sale_order_details.quantity) as total_quantity"),
        ])
            ->leftJoin('sale_order_details', 'sale_order_details.product_id', 'products.id')
            ->leftJoin('sale_orders', 'sale_orders.id', 'sale_order_details.sale_order_id')
            ->leftJoin('units', 'units.id', 'products.unit_id')
            ->whereIn('sale_orders.order_status', [SaleOrderStatusEnum::IN_TRANSIT, SaleOrderStatusEnum::DELIVERED])
            ->groupBy('products.id');
        if ($param) {
            $products = $products->where('sale_orders.created_at', '>=', $param['startTime'])
                ->where('sale_orders.created_at', '<=', $param['endTime']);
        }
        $products = $products->orderBy(DB::raw("sum(sale_order_details.quantity)"), 'desc')
            ->limit(5);
        return $products->get()->toArray();
    }
}
