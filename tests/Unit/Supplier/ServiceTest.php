<?php

use App\Helpers\FileHelper;
use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Http\Requests\Supplier\SupplierDatatableRequest;
use App\Models\Supplier;
use App\Services\Supplier\SupplierServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->supplierService = app(SupplierServiceInterface::class);
});

it('Supplier/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new SupplierDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'name',
            'email',
            'phone',
            'address'
        ],
        [
            'orders' => [
                'code' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->supplierService->paginate($params);
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
    //check order by
    $sortedData = $data['data'];
    usort($sortedData, function ($a, $b) {
        return $a['code'] <=> $b['code'];
    });
    $this->assertEquals($sortedData, $data['data']);
});

it('Supplier/Paginate - Test the filter feature', function () {
    $datatableRequest = new SupplierDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'name',
            'email',
            'phone',
            'address'
        ],
        [
            'searchFields' => [
                'code' => 'SUP001'
            ],
            'orders' => [
                'code' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->supplierService->paginate($params);
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
    $this->assertTrue($data['data'][0]['code'] == 'SUP001');
});

it('Supplier/Paginate - Test the search feature', function () {
    $datatableRequest = new SupplierDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'name',
            'email',
            'phone',
            'address'
        ],
        [
            'globalSearch' => 'Supplier 1',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->supplierService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $suppliers = $data->jsonSerialize();
    $expect2 = false;
    foreach ($suppliers['data'] as $supplier) {
        if (
            preg_match('/Supplier 1/', strval($supplier['name'])) ||
            preg_match('/Supplier 1/', strval($supplier['code'])) ||
            preg_match('/Supplier 1/', strval(implode(', ', [
                $supplier['detail_address'],
                $supplier['city'],
                $supplier['province'],
                $supplier['country']
            ])))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

it('Supplier/Create - Check create feature', function () {
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);
    $dataCreate = [
        'name' => 'supplier',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'email' => 'suppliertest2@gmail.com',
        'phone' => "+12505550199",
        'logo' => $file
    ];
    $supplier = $this->supplierService->create($dataCreate);
    // check data type
    $this->assertInstanceOf(Supplier::class, $supplier);
    // check data return
    $this->assertEquals($supplier->name, 'supplier');
    $supplierDatabase = Supplier::where('name', 'supplier')->first();
    $this->assertNotNull($supplierDatabase);
    //check logo
    Storage::disk('public')->assertExists('suppliers/' . $supplier->logo);
    //check log
    $this->assertEquals(ActionLogEnum::CREATED, $supplierDatabase->activities->first()->event);
});

it('Supplier/Show - Check supplier not found', function () {
    expect(fn() => $this->supplierService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('Supplier/Show - Check success', function () {
    $supplierDatabase = Supplier::find(2);
    $supplierService = $this->supplierService->findById(2);
    $this->assertEquals($supplierDatabase, $supplierService);
});

it('Supplier/Update - Check Supplier not found', function () {
    $dataUpdate = [
        'name' => 'supplier',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'email' => 'suppliertest2@gmail.com',
        'phone' => "+12505550199",
    ];
    expect(fn() => $this->supplierService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

//update
it('Supplier/Update - Check update feature', function () {
    $supplierDatabase = Supplier::find(3);
    $dataUpdate = [
        'name' => 'supplier',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'email' => 'suppliertest3@gmail.com',
        'phone' => "+12505550199",
    ];
    $supplier = $this->supplierService->update($dataUpdate, $supplierDatabase->id);
    // check data type
    $this->assertInstanceOf(Supplier::class, $supplier);
    $supplierInDatabase = Supplier::find($supplierDatabase->id);
    // check data in database
    $supplierArray = $supplierInDatabase->toArray();
    $this->assertEquals($dataUpdate, array_intersect_assoc($supplierArray, $dataUpdate));
    //check log
    $log = $supplier->activities()->orderBy('id', 'desc')->first();
    $this->assertEquals(ActionLogEnum::UPDATED, $log->event);
    $dataChanged = $log->properties['attributes'] ?? [];
    $dataBeforeChanged = $log->properties['old'] ?? [];
    foreach ($dataUpdate as $key => $expectedValue) {
        if (array_key_exists($key, $dataChanged)) {
            $this->assertEquals($expectedValue, $dataChanged[$key]);
        }
    }
    foreach ($dataUpdate as $key => $expectedValue) {
        if (array_key_exists($key, $dataBeforeChanged)) {
            $this->assertEquals($supplierDatabase[$key], $dataBeforeChanged[$key]);
        }
    }
});

it('Supplier/Delete - Check supplier not found', function () {
    expect(fn() => $this->supplierService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('Supplier/Delete - Check success', function () {
    $this->assertTrue($this->supplierService->delete(2));
    $supplier = Supplier::find(2);
    $this->assertNull($supplier);
    //check log and check soft delete
    $supplier = Supplier::withTrashed()->find(2);
    $this->assertNotNull($supplier);
    $this->assertEquals(ActionLogEnum::DELETED, $supplier->activities->first()->event);
});
