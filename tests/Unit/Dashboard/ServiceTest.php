<?php

use App\Enums\SaleOrderStatusEnum;
use App\Models\Product;
use App\Models\ProductLocation;
use App\Models\PurchaseOrder;
use App\Models\SaleOrder;
use App\Services\Dashboard\DashboardServiceInterface;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->dashboardService = app(DashboardServiceInterface::class);
});

it('should return current stock', function () {
    $currentStock = $this->dashboardService->currentStock();
    //check order by
    $sortedData = $currentStock;
    usort($sortedData, function ($a, $b) {
        return $b['quantity'] <=> $a['quantity'];
    });
    // check data
    Product::select([
        'products.*',
        'u.symbol',
        DB::raw("sum(pl.quantity) as quantity")
    ])->leftJoin('units as u', 'u.id', 'products.unit_id')
        ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')
        ->orderBy(DB::raw("sum(pl.quantity)"), 'desc')
        ->groupBy('products.id')
        ->limit(5)
        ->get()
        ->each(function ($item, $key) use ($sortedData) {
            $this->assertEquals($item->quantity, $sortedData[$key]['quantity']);
        });
    $this->assertEquals($sortedData, $currentStock);
});

it('should return low stock', function () {
    $lowStock = $this->dashboardService->lowStock();
    //check order by
    $sortedData = $lowStock;
    usort($sortedData, function ($a, $b) {
        return $a['quantity'] <=> $b['quantity'];
    });
    // check data
    Product::select([
        'products.*',
        'u.symbol',
        DB::raw("COALESCE(sum(pl.quantity), 0) as quantity")
    ])
        ->leftJoin('units as u', 'u.id', 'products.unit_id')
        ->leftJoin('product_locations as pl', 'pl.product_id', 'products.id')
        ->groupBy('products.id')
        ->havingRaw("COALESCE(sum(pl.quantity), 0) < products.min_quantity")
        ->orderBy(DB::raw("COALESCE(sum(pl.quantity), 0)"), 'asc')
        ->limit(5)
        ->get()
        ->each(function ($item, $key) use ($sortedData) {
            $this->assertEquals($item->quantity, $sortedData[$key]['quantity']);
        });
    $this->assertEquals($sortedData, $lowStock);
});

it('should return statistic', function () {
    $statistic = $this->dashboardService->statistic();
    $totalInventoryValue = Product::select([
        DB::raw("sum(products.unit_price * pl.quantity) as total_inventory_value")
    ])
        ->join('product_locations as pl', 'pl.product_id', 'products.id')
        ->where('products.deleted_at', null)
        ->value('total_inventory_value');
    $averageInventoryValue = ProductLocation::select([
        DB::raw("sum(product_locations.unit_cost * product_locations.quantity)/sum(product_locations.quantity) as average_inventory_value")
    ])
        ->value('average_inventory_value');
    $this->assertIsArray($statistic);
    $this->assertEquals($statistic['total_purchase_order'], PurchaseOrder::count());
    $this->assertEquals($statistic['total_sales_order'], SaleOrder::count());
    $this->assertEquals($statistic['total_inventory_value'], $totalInventoryValue);
    $this->assertEquals($statistic['average_inventory_value'], $averageInventoryValue);
});


it('should return recentOrders', function () {
    $orders = $this->dashboardService->recentOrders();
    SaleOrder::select([
        'sale_orders.*',
        DB::raw("concat(c.first_name, ' ', c.last_name) as customer_name"),
        DB::raw("sum(sod.quantity) as total_quantity"),
    ])
        ->leftJoin('customers as c', 'c.id', 'sale_orders.customer_id')
        ->leftJoin('sale_order_details as sod', 'sod.sale_order_id', 'sale_orders.id')
        ->orderBy('sale_orders.created_at', 'desc')
        ->groupBy('sale_orders.id')
        ->limit(5)->get()
        ->each(function ($item, $key) use ($orders) {
            $this->assertEquals($item->id, $orders[$key]['id']);
        });
});


it('should return topSelling', function () {
    $orders = $this->dashboardService->topSelling([]);
    $products = Product::select([
        'products.*',
        'units.symbol',
        DB::raw("sum(sale_order_details.quantity) as total_quantity"),
    ])
        ->leftJoin('sale_order_details', 'sale_order_details.product_id', 'products.id')
        ->leftJoin('sale_orders', 'sale_orders.id', 'sale_order_details.sale_order_id')
        ->leftJoin('units', 'units.id', 'products.unit_id')
        ->whereIn('sale_orders.order_status', [SaleOrderStatusEnum::IN_TRANSIT, SaleOrderStatusEnum::DELIVERED])
        ->groupBy('products.id')
        ->orderBy(DB::raw("sum(sale_order_details.quantity)"), 'desc')
        ->limit(5)->get()->each(function ($item, $key) use ($orders) {
            $this->assertEquals($item->id, $orders[$key]['id']);
        });
});

it('should return pendingOrders', function () {
    $orders = $this->dashboardService->pendingOrders();
    $saleOrder = SaleOrder::select([
        'sale_orders.*',
        DB::raw("concat(c.first_name, ' ', c.last_name) as customer_name"),
        DB::raw("sum(sod.quantity) as total_quantity"),
    ])
        ->leftJoin('customers as c', 'c.id', 'sale_orders.customer_id')
        ->leftJoin('sale_order_details as sod', 'sod.sale_order_id', 'sale_orders.id')
        ->whereIn('sale_orders.order_status', [SaleOrderStatusEnum::CONFIRM, SaleOrderStatusEnum::DRAFT])
        ->orderBy('sale_orders.created_at', 'desc')
        ->groupBy('sale_orders.id')
        ->limit(5)->get()
        ->each(function ($item, $key) use ($orders) {
            $this->assertEquals($item->id, $orders[$key]['id']);
        });
});
