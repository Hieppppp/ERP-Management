<?php

use App\Enums\ReturnOrderStatusEnum;
use App\Helpers\CustomRoute;
use App\Http\Requests\ReturnOrder\ReturnOrderCreateRequest;
use App\Http\Requests\ReturnOrder\ReturnOrderUpdateRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - ReturnOrder/ReturnOrderCreateRequest - Check passes validation when data is valid', function () {
    $request = new ReturnOrderCreateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute();
    });
    $data = [
        'purchase_order_id' => 5,
        'scheduled_date' => date('Y-m-d'),
        'products' => [
            [
                'product_location_id' => 1,
                'demand_quantity' => 10,
            ]
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertFalse($validator->fails());
});

it('Request - ReturnOrder/ReturnOrderCreateRequest - Check failed validation when data is invalid', function () {
    $request = new ReturnOrderCreateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute();
    });
    $data = [
        'purchase_order_id' => 5,
        'scheduled_date' => date('Y-m-d', strtotime('-10 day', strtotime(date('Y-m-d')))),
        'products' => [
            [
                'product_location_id' => 1,
                'demand_quantity' => 12,
            ]
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'products.0.demand_quantity', 'scheduled_date'
    ]));
});

it('Request - ReturnOrder/ReturnOrderUpdateRequest - Check pass validation when data is valid - done update', function () {
    $request = new ReturnOrderUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute();
    });
    $data = [
        'status' => ReturnOrderStatusEnum::DONE,
        'scheduled_date' => date('Y-m-d', strtotime('+10 day', strtotime(date('Y-m-d')))),
        'products' => [
            [
                'return_order_detail_id' => 1,
                'quantity' => 10,
            ],
            [
                'return_order_detail_id' => 2,
                'quantity' => 10,
            ],
            [
                'return_order_detail_id' => 3,
                'quantity' => 10,
            ]
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertFalse($validator->fails());
});

it('Request - ReturnOrder/ReturnOrderUpdateRequest - Check pass validation when data is valid - cancel update', function () {
    $request = new ReturnOrderUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute();
    });
    $data = [
        'status' => ReturnOrderStatusEnum::CANCEL
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertFalse($validator->fails());
});

it('Request - ReturnOrder/ReturnOrderUpdateRequest - Check failed validation when data is invalid', function () {
    $request = new ReturnOrderUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute();
    });
    $data = [
        'status' => ReturnOrderStatusEnum::DONE,
        'scheduled_date' => date('Y-m-d', strtotime('-10 day', strtotime(date('Y-m-d')))),
        'products' => [
            [
                'return_order_detail_id' => 1,
                'quantity' => 10,
            ],
            [
                'return_order_detail_id' => 2,
                'quantity' => 15,
            ],
            [
                'return_order_detail_id' => 3,
                'quantity' => 10,
            ]
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'products.1.quantity', 'scheduled_date'
    ]));
});
