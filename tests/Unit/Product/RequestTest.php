<?php

use App\Helpers\CustomRoute;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

//Create
it('Request - Product/ProductCreateRequest - Check passes validation when data is valid', function () {
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);
    $validator = Validator::make([
        'name' => 'Product 1',
        'unit_id' => 1,
        'min_quantity' => 12,
        'max_quantity' => 43,
        'category_id' => 1,
        'unit_price' => 1000,
        'image' => [$file],
        'supplier_id' => [[
            'id' => 1,
            'price' => 2000
        ]],
        'child_products' => [2, 3],
        'parent_products' => [4, 5]
    ], (new ProductCreateRequest())->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Product/ProductCreateRequest - Check fail validation when data is not valid', function () {
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(6036);
    $validator = Validator::make([], (new ProductCreateRequest())->rules());
    //check required
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name',
        'unit_id',
        'min_quantity',
        'max_quantity',
        'category_id',
        'unit_price'
    ]));

    //check unique name
    $validator = Validator::make([
        'name' => 'iPhone'
    ], (new ProductCreateRequest())->rules());

    $this->assertTrue($validator->errors()->has([
        'name'
    ]));

    //check exists unit, category, tax, supplier, parent_products, child_products
    $validator = Validator::make([
        'unit_id' => 1000,
        'category_id' => 2000,
        'parent_products' => [
            122222,
            12121212
        ],
        'child_products' => [
            10000000,
            100000000
        ],
        'image' => [
            $file
        ]

    ], (new ProductCreateRequest())->rules());
    $this->assertTrue($validator->errors()->has([
        'unit_id',
        'category_id',
        'parent_products.0',
        'parent_products.1',
        'child_products.0',
        'child_products.1',
        'image.0'
    ]));
});

// //update

it('Request - Product/ProductUpdateRequest - Check passes validation when data is valid', function () {
    $request = new ProductUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['id' => 1]);
    });
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);
    $validator = Validator::make([
        'name' => 'Product 1',
        'unit_id' => 1,
        'min_quantity' => 12,
        'max_quantity' => 43,
        'category_id' => 1,
        'unit_price' => 1000,
        'image' => [$file],
        'supplier_id' => [],
        'child_products' => [],
        'parent_products' => []
    ], ($request)->rules());
    $this->assertFalse($validator->fails());
});

it('Request - Product/ProductUpdateRequest - Check fail validation when data is not valid', function () {
    $request = new ProductUpdateRequest();
    $request->setRouteResolver(function () {
        return new CustomRoute(['product' => 1]);
    });
    $validator = Validator::make([], ($request)->rules());
    //check required
    $this->assertTrue($validator->fails());
    $this->assertTrue($validator->errors()->has([
        'name',
        'unit_id',
        'min_quantity',
        'max_quantity',
        'category_id',
        'unit_price'
    ]));

    //check unique name
    $validator = Validator::make([
        'name' => 'Barbie Doll'
    ], ($request)->rules());

    $this->assertTrue($validator->errors()->has([
        'name'
    ]));

    //check exists unit, category, tax, supplier, parent_products, child_products
    $validator = Validator::make([
        'unit_id' => 1000,
        'category_id' => 2000,
        'parent_products' => [
            122222,
            12121212
        ],
        'child_products' => [
            10000000,
            100000000
        ],

    ], ($request)->rules());
    $this->assertTrue($validator->errors()->has([
        'unit_id',
        'category_id',
        'parent_products.0',
        'parent_products.1',
        'child_products.0',
        'child_products.1',
    ]));
});
