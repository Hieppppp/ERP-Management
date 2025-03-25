<?php

use App\Models\InventoryLog;
use App\Models\ProductLocation;
use App\Models\ProductPurchaseOrder;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProductShelve;
use App\Models\User;
use App\Services\Inventory\InventoryServiceInterface;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $this->inventoryService = app(InventoryServiceInterface::class);
});

it('Inventory/Update - Check update feature', function () {
    $user = User::where('username', 'admin')->first();
    $this->actingAs($user);

    $dataUpdate = [
        'current_quantity' => '10',
        'new_quantity' => '100',
        'note' => '<p>note</p>'
    ];
    $inventory = $this->inventoryService->update($dataUpdate, 1);
    $this->assertInstanceOf(ProductLocation::class, $inventory);

    $inventoryInDatabase = ProductLocation::find(1);
    $this->assertEquals($inventoryInDatabase->quantity, $dataUpdate['new_quantity']);

    $inventoryLog = InventoryLog::where('product_location_id', 1)->orderBy('id', 'desc')->first();
    $this->assertEquals($inventoryLog->before_quantity, $dataUpdate['current_quantity']);
    $this->assertEquals($inventoryLog->after_quantity, $dataUpdate['new_quantity']);
    $this->assertEquals($inventoryLog->user_id, $user->id);
});

it('Inventory/Create - Check create feature', function () {
    $user = User::where('username', 'admin')->first();
    $this->actingAs($user);

    $dataUpdate = [
        'product_id' => 1,
        'supplier_id' => 1,
        'warehouse_id' => 1,
        'shelves' => [
            [
                'id' => 1,
                'quantity' => 20
            ],
            [
                'id' => 2,
                'quantity' => 20
            ]
        ]
    ];
    $createdPurchaseOrder = $this->inventoryService->create($dataUpdate);
    $this->assertInstanceOf(PurchaseOrder::class, $createdPurchaseOrder);

    //test success insert data to purchase_orders table
    $purchaseOrderInDatabase = PurchaseOrder::find($createdPurchaseOrder->id);
    $this->assertEquals($purchaseOrderInDatabase->supplier_id, $dataUpdate['supplier_id']);
    $this->assertEquals($purchaseOrderInDatabase->warehouse_id, $dataUpdate['warehouse_id']);

    //test success insert data to puchase_product_shelves table
    $purchaseProductShelveInDatabase = PurchaseProductShelve::where('purchase_order_id', $createdPurchaseOrder->id)->get();
    $this->assertEquals(count($purchaseProductShelveInDatabase), 2);

    //test success insert data to product_locations table
    $productLocationInDatabase = ProductLocation::where('purchase_order_id', $createdPurchaseOrder->id)->get();
    $this->assertEquals(count($productLocationInDatabase), 2);

    //test success insert data to product_ppurchase_orders table
    $productPurchaseOrderInDatabase = ProductPurchaseOrder::where('purchase_order_id', $createdPurchaseOrder->id)->first();
    $this->assertEquals($productPurchaseOrderInDatabase->quantity, 40);
});

it('updates existing product locations and creates inventory logs', function () {
    $productLocation = ProductLocation::create([
        'product_id' => 1,
        'purchase_order_id' => 1,
        'shelve_id' => 1,
        'quantity' => 100,
        'unit_cost' => 10,
    ]);

    $shelves = [
        ['id' => 2, 'quantity' => 50],
        ['id' => 3, 'quantity' => 30],
    ];

    $params = ['shelves' => $shelves];

    Auth::shouldReceive('id')->andReturn(1);

    $result = $this->inventoryService->movement($params, $productLocation->id);
    $this->assertTrue($result);
    $this->assertEquals(3, InventoryLog::count());
    $productLocation->refresh();
    $this->assertEquals(100 - 80, $productLocation->quantity);
});
