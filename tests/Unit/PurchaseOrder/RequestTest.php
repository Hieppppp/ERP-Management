<?php

use App\Helpers\CustomRoute;
use App\Http\Requests\PurchaseOrder\PurchaseOrderCreateRequest;
use App\Http\Requests\PurchaseOrder\PurchaseOrderReceiveProductRequest;
use App\Http\Requests\PurchaseOrder\PurchaseOrderUpdateRequest;
use App\Http\Requests\PurchaseOrder\PutProductRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - PurchaseOrder/PurchaseOrderCreateRequest - Check passes validation when data is valid', function () {
    $validator = Validator::make([
        'supplier_id' => 1,
        'warehouse_id' => 1,
        'scheduled_date' => date('Y-m-d'),
        'products' => [
            [
                'id' => 1,
                'quantity' => 12,
                'unit_cost' => 10,
            ]
        ]
    ], (new PurchaseOrderCreateRequest())->rules());
    $this->assertFalse($validator->fails());
});

it('Request - PurchaseOrder/PurchaseOrderCreateRequest - Check fail validation when data is not valid', function () {
    // check required
    $validator = Validator::make([], (new PurchaseOrderCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'supplier_id',
        'products',
        'warehouse_id'
    ]));

    //check exists name and check quantity
    $validator = Validator::make([
        'supplier_id' => 10000,
        'warehouse_id' => 1000,
        'products' => [
            [
                'id' => 10000,
                'quantity' => -10
            ]
        ]
    ], (new PurchaseOrderCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'supplier_id', "products.0.id", "products.0.quantity", "products.0.unit_cost"
    ]));
    //check scheduled_date
    $validator = Validator::make([
        'scheduled_date' => date('Y-m-d', strtotime('-10 day', strtotime(date('Y-m-d'))))
    ], (new PurchaseOrderCreateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'scheduled_date'
    ]));
});

//update

it('Request - PurchaseOrder/PurchaseOrderUpdateRequest - Check passes validation when data is valid', function () {
    $request = new PurchaseOrderUpdateRequest();
    $validator = Validator::make([
        'supplier_id' => 1,
        'scheduled_date' => date('Y-m-d'),
        'warehouse_id' => 1,
        'products' => [
            [
                'id' => 1,
                'quantity' => 12,
                'unit_cost' => 10,
            ]
        ]
    ], ($request)->rules());
    $this->assertFalse($validator->fails());
});

it('Request - PurchaseOrder/PurchaseOrderUpdateRequest - Check fail validation when data is not valid', function () {
    // check required
    $validator = Validator::make([], (new PurchaseOrderUpdateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'supplier_id',
        'products',
        'warehouse_id'
    ]));

    //check exists name and check quantity
    $validator = Validator::make([
        'supplier_id' => 10000,
        'products' => [
            [
                'id' => 10000,
                'quantity' => -10
            ]
        ]
    ], (new PurchaseOrderUpdateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'supplier_id', "products.0.id", "products.0.quantity", "products.0.unit_cost"
    ]));
    //check scheduled_date
    $validator = Validator::make([
        'scheduled_date' => date('Y-m-d', strtotime('-10 day', strtotime(date('Y-m-d'))))
    ], (new PurchaseOrderUpdateRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'scheduled_date'
    ]));
});

it('Request - PurchaseOrder/PurchaseOrderReceiveProductRequest - Check passes validation when data is valid', function () {
    $request = new PurchaseOrderReceiveProductRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['id' => 2]);
    });
    $data = [
        'received_note' => 'Has received the goods',
        'products' => [
            [
                'id' => 4,
                'received_quantity' => 10,
                'sku' => 'sku12'
            ],
            [
                'id' => 5,
                'received_quantity' => 10,
                'sku' => 'sku13'
            ],
            [
                'id' => 6,
                'received_quantity' => 10,
                'sku' => 'sku14'
            ],
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertFalse($validator->fails());
});

it('Request - PurchaseOrder/PurchaseOrderReceiveProductRequest - Check fail validation when data is not valid', function () {
    // check required
    $validator = Validator::make([], (new PurchaseOrderReceiveProductRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'products',
    ]));

    $request = new PurchaseOrderReceiveProductRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['id' => 2]);
    });
    $data = [
        'received_note' => 1,
        'products' => [
            [
                'id' => 4,
                'received_quantity' => 12,
                'sku' => 'sku1'
            ],
            [
                'id' => 5,
                'received_quantity' => 14,
                'sku' => 'sku12'
            ],
            [
                'id' => 6,
                'received_quantity' => 10,
                'sku' => 'sku12'
            ],
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'received_note', "products.0.received_quantity", "products.1.received_quantity", "products"
    ]));
});

it('Request - PurchaseOrder/PutProductRequest - Check fail validation when data is not valid', function () {
    // check required
    $validator = Validator::make([], (new PutProductRequest())->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'shelves',
    ]));

    $request = new PutProductRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['purchaseOrderId' => 1000, 'productId' => 10000]);
    });
    $data = [
        'shelves' => [
            [
                'id' => 4,
                'quantity' => 12
            ],
            [
                'id' => 5,
                'quantity' => 14
            ],
            [
                'id' => 6,
                'quantity' => 10
            ],
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        "shelves.0.quantity", "shelves.1.quantity", "shelves.2.quantity"
    ]));
});

it('Request - PurchaseOrder/PutProductRequest - Check passes validation when data is valid', function () {
    $request = new PutProductRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['purchaseOrderId' => 3, 'productId' => 1]);
    });
    $data = [
        'shelves' => [
            [
                'id' => 5,
                'quantity' => 5
            ],
            [
                'id' => 6,
                'quantity' => 5
            ]
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertFalse($validator->fails());
});
