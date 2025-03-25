<?php

use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Http\Requests\Shelve\ShelveDatatableRequest;
use App\Models\Shelve;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use App\Services\Shelve\ShelveServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->shelveService = app(ShelveServiceInterface::class);
    $this->purchaseOrderService = app(PurchaseOrderServiceInterface::class);
});

it('Shelve/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new ShelveDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'warehouse',
            'location'
        ],
        [
            'orders' => [
                'id' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->shelveService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $data = $data->jsonSerialize();
    $expect2 = count($data['data']) <= 5;
    // check order
    $userId = $userIdSort = array_column($data['data'], 'id');
    asort($userIdSort);
    $expect4 = $userId === $userIdSort;
    //check data type
    expect($expect1)->toBeTrue();
    //check total data
    expect($expect2)->toBeTrue();
    //check order by
    expect($expect4)->toBeTrue();
});

it('Shelve/Paginate - Test the filter feature', function () {
    $datatableRequest = new ShelveDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'warehouse',
            'location'
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
    $data = $this->shelveService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $data = $data->jsonSerialize();
    $expect2 = ($data['data'][0]['id'] == 1);
    //check data type
    expect($expect1)->toBeTrue();
    //check data by Id
    expect($expect2)->toBeTrue();
});

it('Shelve/Paginate - Test the search feature', function () {
    $datatableRequest = new ShelveDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'warehouse',
            'location'
        ],
        [
            'globalSearch' => 'Shelf 1',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->shelveService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $shelves = $data->jsonSerialize();
    $expect2 = false;
    foreach ($shelves['data'] as $shelve) {
        if (
            preg_match('/Shelf 1/', strval($shelve['name'])) ||
            preg_match('/Shelf 1/', strval($shelve['id'])) ||
            preg_match('/Shelf 1/', strval($shelve['warehouse'])) ||
            preg_match('/Shelf 1/', strval($shelve['location']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data type
    expect($expect1)->toBeTrue();
    //check data by name
    expect($expect2)->toBeTrue();
});

it('Shelve/Create - Check create feature', function () {
    $dataCreate = [
        'name' => 'Shelf 10',
        'location' => 'In the middle of the warehouse',
        'warehouse_id' => 1
    ];
    $shelve = $this->shelveService->create($dataCreate);
    $expect1 = $shelve instanceof Shelve;
    $expect2 = ($shelve->name == 'Shelf 10');
    $shelveDatabase = Shelve::where('name', 'Shelf 10')->first();
    $expect3 = ($shelveDatabase != null);
    // check data type
    expect($expect1)->toBeTrue();
    // check data return
    expect($expect2)->toBeTrue();
    // check data in database
    expect($expect3)->toBeTrue();
    //check log
    $this->assertEquals(ActionLogEnum::CREATED, $shelveDatabase->activities->first()->event);
});

it('Shelve/Show - Check shelf not found', function () {
    expect(fn () => $this->shelveService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('Shelve/Show - Check success', function () {
    $shelveDatabase = Shelve::find(2);
    $shelveService = $this->shelveService->findById(2);
    expect($shelveDatabase == $shelveService)->toBeTrue();
});

it('Shelve/Update - Check shelf not found', function () {
    $dataUpdate = [
        'name' => 'Shelf 10',
        'location' => 'In the middle of the warehouse',
        'warehouse_id' => 2
    ];
    expect(fn () => $this->shelveService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('Shelve/Update - Check update feature', function () {
    $dataUpdate = [
        'name' => 'Shelf 10',
        'location' => 'In the middle of the warehouse',
        'warehouse_id' => 2
    ];
    $shelve = $this->shelveService->update($dataUpdate, 3);
    $expect1 = $shelve instanceof Shelve;
    $shelveInDatabase = Shelve::find(3);
    $expect2 = (
        $shelve->name == $shelveInDatabase->name &&
        $shelve->location == $shelveInDatabase->location &&
        $shelve->warehouse_id == $shelveInDatabase->warehouse_id
    );
    // check data type
    expect($expect1)->toBeTrue();
    // check data in database
    expect($expect2)->toBeTrue();
    //check log
    $this->assertEquals(ActionLogEnum::UPDATED, $shelveInDatabase->activities->first()->event);
});
it('Shelve/Delete - Check warehouse not found', function () {
    expect(fn () => $this->shelveService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('Shelve/Delete - Check success', function () {
    expect($this->shelveService->delete(2))->toBeTrue();
    $shelve = Shelve::find(2);
    expect($shelve == null)->toBeTrue();
    //check log and check soft delete
    $shelve = Shelve::withTrashed()->find(2);
    $this->assertNotNull($shelve);
    $this->assertEquals(ActionLogEnum::DELETED, $shelve->activities->first()->event);
});

it('Shelve/getDetail - Check Not Found', function () {
    expect(fn () => $this->shelveService->getDetail(100000))->toThrow(ModelNotFoundException::class);
});

it('Shelve/getDetail - Check success', function () {
    $purchaseOrder = $this->purchaseOrderService->putProductOnShelf(3, 3, [
        'shelves' => [
            [
                'id' => 1,
                'quantity' => 15
            ]
        ]
    ]);
    $this->assertTrue($purchaseOrder);
    $shelveDetail = $this->shelveService->getDetail(1);
    $this->assertNotNull($shelveDetail['products']);
    $shelveDetail['products']->each(function ($product) {
        if ($product->id == 3) {
            $this->assertNotNull($product->total_quantity);
            $this->assertEquals(15, $product->total_quantity);
        }
    });
});
