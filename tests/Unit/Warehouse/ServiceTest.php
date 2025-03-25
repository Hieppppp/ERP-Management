<?php

use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Http\Requests\Warehouse\WarehouseDatatableRequest;
use App\Models\Warehouse;
use App\Services\Warehouse\WarehouseServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->warehouseService = app(WarehouseServiceInterface::class);
});

it('Warehouse/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new WarehouseDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'detail_address',
            'city',
            'province',
            'country'
        ],
        [
            'orders' => [
                'id' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->warehouseService->paginate($params);
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

it('Warehouse/Paginate - Test the filter feature', function () {
    $datatableRequest = new WarehouseDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'detail_address',
            'city',
            'province',
            'country'
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
    $data = $this->warehouseService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $data = $data->jsonSerialize();
    $expect2 = ($data['data'][0]['id'] == 1);
    //check data type
    expect($expect1)->toBeTrue();
    //check data by Id
    expect($expect2)->toBeTrue();
});

it('Warehouse/Paginate - Test the search feature', function () {
    $datatableRequest = new WarehouseDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'detail_address',
            'city',
            'province',
            'country'
        ],
        [
            'globalSearch' => 'warehouse 1',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->warehouseService->paginate($params);
    $expect1 = $data instanceof LengthAwarePaginator || $data instanceof Paginator;
    $warehouses = $data->jsonSerialize();
    $expect2 = false;
    foreach ($warehouses['data'] as $warehouse) {
        if (
            preg_match('/Warehouse 1/', strval($warehouse['name'])) ||
            preg_match('/Warehouse 1/', strval($warehouse['id'])) ||
            preg_match('/Warehouse 1/', strval($warehouse['detail_address'])) ||
            preg_match('/Warehouse 1/', strval($warehouse['city'])) ||
            preg_match('/Warehouse 1/', strval($warehouse['province'])) ||
            preg_match('/Warehouse 1/', strval($warehouse['country']))
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

it('Warehouse/Create - Check create feature', function () {
    $dataCreate = [
        'name' => 'warehouse 12',
        'country' => "Canada",
        'province' => "British Columbia",
        'city' => 'Grande Prairie',
        'detail_address' => "24",
        'postal_code' => 100000
    ];
    $warehouse = $this->warehouseService->create($dataCreate);
    $expect1 = $warehouse instanceof Warehouse;
    $expect2 = ($warehouse->name == 'warehouse 12');
    $warehouseDatabase = Warehouse::where('name', 'warehouse 12')->first();
    $expect3 = ($warehouseDatabase != null);
    // check data type
    expect($expect1)->toBeTrue();
    // check data return
    expect($expect2)->toBeTrue();
    // check data in database
    expect($expect3)->toBeTrue();

    //check log
    $this->assertEquals(ActionLogEnum::CREATED, $warehouseDatabase->activities->first()->event);
});

it('Warehouse/Show - Check warehouse not found', function () {
    expect(fn () => $this->warehouseService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('Warehouse/Show - Check success', function () {
    $warehouseDatabase = Warehouse::find(2);
    $warehouseService = $this->warehouseService->findById(2);
    expect($warehouseDatabase == $warehouseService)->toBeTrue();
});

it('Warehouse/Update - Check Warehouse not found', function () {
    $dataUpdate = [
        'name' => "warehouse 10",
        'country' => "Canada",
        'province' => 'British Columbia',
        'city' => 'Grande Prairie',
        'detail_address' => '24',
        'postal_code' => 100000
    ];
    expect(fn () => $this->warehouseService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('Warehouse/Update - Check update feature', function () {
    $dataUpdate = [
        'name' => "warehouse 10",
        'country' => "Canada",
        'province' => 'British Columbia',
        'city' => 'Grande Prairie',
        'detail_address' => '24',
        'postal_code' => 100000
    ];
    $warehouse = $this->warehouseService->update($dataUpdate, 3);
    $expect1 = $warehouse instanceof Warehouse;
    $warehouseInDatabase = Warehouse::find(3);
    $expect2 = (
        $warehouse->name == $warehouseInDatabase->name &&
        $warehouse->country == $warehouseInDatabase->country &&
        $warehouse->province == $warehouseInDatabase->province &&
        $warehouse->city == $warehouseInDatabase->city &&
        $warehouse->detail_address == $warehouseInDatabase->detail_address
    );
    // check data type
    expect($expect1)->toBeTrue();
    // check data in database
    expect($expect2)->toBeTrue();
    //check log
    $this->assertEquals(ActionLogEnum::UPDATED, $warehouseInDatabase->activities->first()->event);
});
it('Warehouse/Delete - Check warehouse not found', function () {
    expect(fn () => $this->warehouseService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('Warehouse/Delete - Check success', function () {
    expect($this->warehouseService->delete(2))->toBeTrue();
    $warehouse = Warehouse::find(2);
    expect($warehouse == null)->toBeTrue();
    //check log and check soft delete
    $warehouse = Warehouse::withTrashed()->find(2);
    $this->assertNotNull($warehouse);
    $this->assertEquals(ActionLogEnum::DELETED, $warehouse->activities->first()->event);
});
