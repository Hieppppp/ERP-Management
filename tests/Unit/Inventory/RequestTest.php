<?php

use App\Helpers\CustomRoute;
use App\Http\Requests\Inventory\InventoryCreateRequest;
use App\Http\Requests\Inventory\InventoryUpdateRequest;
use Illuminate\Support\Facades\Validator;

it('Request - Inventory/InventoryUpdateRequest - Check passes validation when data is valid', function () {
    $request = new InventoryUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['inventory' => 1]);
    });
    $validator = Validator::make([
        'current_quantity' => '10',
        'new_quantity' => '1',
        'note' => '<p>note</p>'
    ], ($request)->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Inventory/InventoryUpdateRequest - Check passes validation when data is invalid', function () {
    $request = new InventoryUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['inventory' => 1]);
    });
    $validator = Validator::make([
        'current_quantity' => '5',
        'new_quantity' => '-5',
        'note' => '<p>note</p>'
    ], ($request)->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'current_quantity', 'new_quantity'
    ]));
});

it('Request - Inventory/InventoryCreateRequest - Check passes validation when data is valid', function () {
    $request = new InventoryCreateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute();
    });
    $data = [
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
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Inventory/InventoryCreateRequest - Check passes validation when data is invalid', function () {
    $request = new InventoryCreateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute();
    });
    $data = [
        'product_id' => 100,
        'supplier_id' => 1,
        'warehouse_id' => 1,
        'shelves' => [
            [
                'id' => 1,
                'quantity' => 20
            ],
            [
                'id' => 3,
                'quantity' => 20
            ]
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'product_id', 'shelves.1.id'
    ]));
});

