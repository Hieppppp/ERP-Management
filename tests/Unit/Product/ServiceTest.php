<?php

use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Exceptions\ProductDeleteException;
use App\Http\Requests\Product\ProductDatatableRequest;
use App\Models\Product;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->productService = app(ProductServiceInterface::class);
});

it('Product/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new ProductDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'description',
            'category_name',
            'unit_price',
            'unit_name',
            'min_quantity',
            'max_quantity'
        ],
        [
            'orders' => [
                'id' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    //check total data
    $this->assertLessThanOrEqual(5, count($data['data']));
    // check order
    $sortedData = $data['data'];
    usort($sortedData, function ($a, $b) {
        return $a['id'] <=> $b['id'];
    });
    $this->assertEquals($sortedData, $data['data']);
});

it('Product/Paginate - Test the filter feature', function () {
    $datatableRequest = new ProductDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'description',
            'category_name',
            'unit_price',
            'unit_name',
            'min_quantity',
            'max_quantity'
        ],
        [
            'searchFields' => [
                'id' => 1
            ],
            'orders' => [
                'id' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    //check data by Id
    $this->assertTrue($data['data'][0]['id'] == 1);
});

it('Product/Paginate - Test the custom filter feature', function () {
    $datatableRequest = new ProductDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [],
        [
            'additionalFields' => [
                'filters' => [
                    [
                        'column' => 'products.name',
                        'condition' => 'LIKE',
                        'value' => '%Machine%'
                    ],
                    [
                        'column' => 'products.category_id',
                        'condition' => '=',
                        'value' => 1
                    ],
                ]
            ]
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue($data['data'][0]['id'] == 4);

    $dataValidate = DatatableParams::createDatatableParams(
        [],
        [
            'additionalFields' => [
                'filters' => [
                    [
                        'column' => 'products.unit_price',
                        'condition' => '<',
                        'value' => 3000
                    ],
                    [
                        'column' => 'products.unit_price',
                        'condition' => '>',
                        'value' => 1000
                    ]
                ]
            ]
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->paginate($params);
    $data = $data->jsonSerialize();
    $this->assertTrue(count($data['data']) == 2);

    $dataValidate = DatatableParams::createDatatableParams(
        [],
        [
            'additionalFields' => [
                'filters' => [
                    [
                        'column' => 'supplier',
                        'condition' => 'in',
                        'value' => 1
                    ],
                    [
                        'column' => 'supplier',
                        'condition' => 'in',
                        'value' => 3
                    ]
                ]
            ]
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->paginate($params);
    $data = $data->jsonSerialize();
    $this->assertTrue(count($data['data']) == 1);
    $this->assertTrue($data['data'][0]['id'] == 1);

    $dataValidate = DatatableParams::createDatatableParams(
        [],
        [
            'additionalFields' => [
                'filters' => [
                    [
                        'column' => 'quantity',
                        'condition' => 'approaching',
                        'value' => 'minimum'
                    ]
                ]
            ]
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->paginate($params);
    $data = $data->jsonSerialize();
    $this->assertTrue(count($data['data']) == 2);
});

it('Product/Paginate - Test the search feature', function () {
    $datatableRequest = new ProductDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'description',
            'category_name',
            'unit_price',
            'unit_name',
            'min_quantity',
            'max_quantity'
        ],
        [
            'globalSearch' => 'iPhon'
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $products = $data->jsonSerialize();
    $expect2 = false;
    foreach ($products['data'] as $product) {
        if (
            preg_match('/iPhon/', strval($product['name'])) ||
            preg_match('/iPhon/', strval($product['id'])) ||
            preg_match('/iPhon/', strval($product['description'])) ||
            preg_match('/iPhon/', strval($product['unit_price'])) ||
            preg_match('/iPhon/', strval($product['unit_name'])) ||
            preg_match('/iPhon/', strval($product['min_quantity'])) ||
            preg_match('/iPhon/', strval($product['max_quantity'])) ||
            preg_match('/iPhon/', strval($product['category_name']))
        ) {
            $expect2 = true;
            break;
        }
    }
    $this->assertTrue($expect2);
});

it('Product/Create - Check create feature', function () {
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);
    $dataCreate = [
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
    ];
    $product = $this->productService->create($dataCreate);
    // check data type
    $this->assertInstanceOf(Product::class, $product);
    // check data return
    $this->assertEquals($product->name, 'Product 1');
    $productDatabase = Product::where('name', 'Product 1')->with('images')->first();
    $this->assertNotNull($productDatabase);
    //check image
    $this->assertNotNull($productDatabase?->images);
    Storage::disk('public')->assertExists($productDatabase?->images[0]->type . '/' . $productDatabase?->images[0]->name);
});

it('Product/Show - Check shelf not found', function () {
    expect(fn() => $this->productService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('Product/Show - Check success', function () {
    $productService = $this->productService->findById(2);
    $this->assertEquals('Barbie Doll', $productService->name);
});

it('Product/Update - Check shelf not found', function () {
    $dataUpdate = [
        'name' => 'Product 1',
        'unit_id' => 1,
        'min_quantity' => 12,
        'max_quantity' => 43,
        'category_id' => 1,
        'unit_price' => 1000,
        'supplier_id' => [[
            'id' => 1,
            'price' => 2000
        ]],
        'child_products' => [2, 3],
        'parent_products' => [4, 5]
    ];
    expect(fn() => $this->productService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('Product/Update - Check update feature', function () {
    $dataUpdate = [
        'name' => 'Product 1',
        'unit_id' => 1,
        'min_quantity' => 12,
        'max_quantity' => 43,
        'category_id' => 1,
        'unit_price' => 1000,
        'supplier_id' => [[
            'id' => 1,
            'price' => 2000
        ]],
        'child_products' => [2, 3],
        'parent_products' => [4, 5]
    ];
    $product = $this->productService->update($dataUpdate, 3);
    $this->assertInstanceOf(Product::class, $product);
    $productInDatabase = Product::find(3);
    // check data in database
    $this->assertEquals($product->name, $productInDatabase->name);
    $this->assertEquals($product->unit_id, $productInDatabase->unit_id);
    $this->assertEquals($product->min_quantity, $productInDatabase->min_quantity);
    $this->assertEquals($product->max_quantity, $productInDatabase->max_quantity);
    $this->assertEquals($product->category_id, $productInDatabase->category_id);
    $this->assertEquals($product->unit_price, $productInDatabase->unit_price);
});
it('Product/Delete - Check Products not found', function () {
    expect(fn() => $this->productService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('Product/Delete - Check Product Delete Exception', function () {
    expect(fn() => $this->productService->delete(2))->toThrow(ProductDeleteException::class);
});

it('Product/Delete - Check success', function () {
    $this->assertTrue($this->productService->delete(4));
    $product = Product::find(4);
    $this->assertNull($product);
});

it('Product/getActivityProduct - Check Create Product', function () {
    $dataCreate = [
        'name' => 'Product 1',
        'unit_id' => 1,
        'min_quantity' => 12,
        'max_quantity' => 43,
        'category_id' => 1,
        'unit_price' => 1000,
        'supplier_id' => [[
            'id' => 1,
            'price' => 2000
        ]],
        'suppliers' => [
            [
                'id' => 2,
                'price' => 30
            ],
            [
                'id' => 3,
                'price' => 40
            ]
        ],
        'parent_products' => [4, 5]
    ];
    $product = $this->productService->create($dataCreate);
    $log = $this->productService->getActivityProduct($product->id);
    $this->assertEquals($log[0]['event'], ActionLogEnum::CREATED);
});

it('Product/getActivityProduct - Check Update Product', function () {
    $dataUpdate = [
        'name' => 'Product 1',
        'unit_id' => 1,
        'min_quantity' => 12,
        'max_quantity' => 43,
        'category_id' => 1,
        'unit_price' => 1000,
        'supplier_id' => [[
            'id' => 1,
            'price' => 2000
        ]],
        'suppliers' => [
            [
                'id' => 2,
                'price' => 30
            ],
            [
                'id' => 3,
                'price' => 40
            ]
        ],
        'parent_products' => [4, 5]
    ];
    $this->productService->update($dataUpdate, 3);
    $log = $this->productService->getActivityProduct(3);
    $changeData = $log[0]['change'] ?? [];

    $this->assertEquals($changeData['name'], [
        'old' => 'Hot Wheels',
        'new' => 'Product 1'
    ]);

    $this->assertArrayNotHasKey('unit_id', $changeData);
    $this->assertArrayNotHasKey('unit_price', $changeData);

    $this->assertEquals($changeData['min_quantity'], [
        'old' => 65,
        'new' => 12
    ]);

    $this->assertEquals($changeData['max_quantity'], [
        'old' => 87,
        'new' => 43
    ]);

    $this->assertEquals($changeData['category_id'], [
        'old' => 2,
        'new' => 1
    ]);

    $this->assertNotEquals($changeData['suppliers'], []);
    $this->assertNotEquals($changeData['suppliers']['created'], []);

    $this->assertNotEquals($changeData['parent_products'], []);
    $this->assertNotEquals($changeData['parent_products']['created'], []);
});

it('Product/inventory - Test inventory list order', function () {
    $datatableRequest = new ProductDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'shelves_code',
            'Product_code',
            'location',
            'supplier_name',
            'quantity',
            'last_updated_date'
        ],
        [
            'orders' => [
                'id' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->inventory($params, 1);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    //check total data
    $this->assertLessThanOrEqual(5, count($data['data']));
    // check order
    $sortedData = $data['data'];
    usort($sortedData, function ($a, $b) {
        return $a['id'] <=> $b['id'];
    });
    $this->assertEquals($sortedData, $data['data']);
});

it('Product/inventory - Test inventory list column search', function () {
    $datatableRequest = new ProductDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'shelves_code',
            'Product_code',
            'location',
            'supplier_name',
            'quantity',
            'last_updated_date'
        ],
        [
            'searchFields' => [
                'shelves_code' => 'WH5-S8'
            ],
            'orders' => [
                'id' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->productService->inventory($params, 1);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    //check total data
    $this->assertLessThanOrEqual(5, count($data['data']));
    // check order

    $this->assertEquals($data['data'][0]['id'], 4);
});
