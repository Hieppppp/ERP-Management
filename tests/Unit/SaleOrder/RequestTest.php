<?php

use App\Enums\DeliverMethodEnum;
use App\Enums\PaymentTermTypeEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\SaleOrderStatusEnum;
use App\Helpers\CustomRoute;
use App\Http\Requests\SaleOrder\RegisterPaymentRequest;
use App\Http\Requests\SaleOrder\SaleOrderAddStockRequest;
use App\Http\Requests\SaleOrder\SaleOrderCreateRequest;
use App\Http\Requests\SaleOrder\SaleOrderUpdateStatusRequest;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - Invoice/RegisterPaymentRequest - Check passes validation when data is valid', function () {
    $request = new RegisterPaymentRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['invoice' => 2]);
    });
    $data = [
        'payment_type' => PaymentTypeEnum::CHECK,
        'date' => date('Y-m-d'),
        'paid_amount' => 1000
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Invoice/RegisterPaymentRequest - Check failed validation when data is invalid', function () {
    $request = new RegisterPaymentRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['invoice' => 2]);
    });
    $data = [
        'payment_type' => PaymentTypeEnum::CHECK,
        'date' => date('Y-m-d', strtotime('+5 day', strtotime(date('Y-m-d'))))
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'date',
        'paid_amount'
    ]));
});

it('Request - SaleOrder/SaleOrderCreateRequest - Check failed validation when data is invalid', function () {
    $request = new SaleOrderCreateRequest();
    $data = [
        'customer_id' => '1',
        'customer_email' => 'customer1@gmail.com',
        'customer_phone' => '+12505550190',
        'deliver_address' => 'Số 20, Airdrie, Alberta, Canada',
        'customer_address' => 'Số 20, Airdrie, Alberta, Canada',
        'tax_data' => [
            [
                'code' => 'TPS',
                'rate' => 5,
            ]
        ],
        'order_status' => SaleOrderStatusEnum::DRAFT,
        'delivery_method' => DeliverMethodEnum::IN_STORE_PICKUP,
        'payment_term' => PaymentTermTypeEnum::IMMEDIATE_PAYMENT,
        'products' => [
            [
                'id' => 1,
                'discount_amount' => 10,
                'unit_price' => 15,
                'order_quantity' => 0
            ],
            [
                'id' => 2,
                'discount_amount' => 101,
                'unit_price' => 15,
                'order_quantity' => 0
            ],
        ],
        'note' => 'note'
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());

    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'products.0.order_quantity',
        'products.1.discount_amount',
    ]));
});

it('Request - SaleOrder/SaleOrderCreateRequest - Check success validation when data is valid', function () {
    $request = new SaleOrderCreateRequest();
    $data = [
        'customer_id' => '1',
        'customer_email' => 'customer1@gmail.com',
        'customer_phone' => '+12505550190',
        'deliver_address' => 'Số 20, Airdrie, Alberta, Canada',
        'customer_address' => 'Số 20, Airdrie, Alberta, Canada',
        'tax_data' => [
            [
                'code' => 'TPS',
                'rate' => 5,
            ]
        ],
        'order_status' => SaleOrderStatusEnum::DRAFT,
        'delivery_method' => DeliverMethodEnum::IN_STORE_PICKUP,
        'payment_term' => PaymentTermTypeEnum::IMMEDIATE_PAYMENT,
        'products' => [
            [
                'id' => 1,
                'discount_amount' => 10,
                'unit_price' => 15,
                'order_quantity' => 10
            ],
            [
                'id' => 2,
                'discount_amount' => 10,
                'unit_price' => 15,
                'order_quantity' => 5
            ],
        ],
        'note' => 'note',
        'warehouse_id' => 1
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());

    $this->assertFalse($validator->fails());
});

it('Request - SaleOrder/SaleOrderUpdateStatusRequest - Check failed validation when data is invalid', function () {
    $request = new SaleOrderUpdateStatusRequest();
    $data = [
        'order_status' => SaleOrderStatusEnum::DRAFT,
        'note' => 'note'
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());

    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'order_status',
    ]));
});

it('Request - SaleOrder/SaleOrderUpdateStatusRequest - Check success validation when data is valid', function () {
    $request = new SaleOrderUpdateStatusRequest();
    $data = [
        'order_status' => SaleOrderStatusEnum::CONFIRM,
        'note' => 'note'
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());

    $this->assertFalse($validator->fails());
});

it('Request - SaleOrder/SaleOrderAddStockRequest - Check failed validation when data is invalid', function () {
    $request = new SaleOrderAddStockRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute([
            'id' => 5,
            'product_id' => 1,
        ]);
    });
    $data = [
        'inventories' => [
            [
                'id' => 1,
                'select_quantity' => 6,
            ],
            [
                'id' => 4,
                'select_quantity' => 10,
            ],
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());

    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'inventories',
    ]));
});

it('Request - SaleOrder/SaleOrderAddStockRequest - Check success validation when data is valid', function () {
    $request = new SaleOrderAddStockRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute([
            'id' => 5,
            'product_id' => 1,
        ]);
    });
    $data = [
        'inventories' => [
            [
                'id' => 1,
                'select_quantity' => 6,
            ],
            [
                'id' => 4,
                'select_quantity' => 6,
            ],
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());

    $this->assertFalse($validator->fails());
});

it('Request - SaleOrder/validateROG - Check success validation when data is valid', function () {
    $request = new SaleOrderAddStockRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute([
            'id' => 5,
            'product_id' => 1,
        ]);
    });
    $data = [
        'inventories' => [
            [
                'id' => 1,
                'select_quantity' => 6,
            ],
            [
                'id' => 4,
                'select_quantity' => 6,
            ],
        ]
    ];
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());

    $this->assertFalse($validator->fails());
});
