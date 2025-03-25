<?php

use App\Http\Requests\Customer\CustomerDatatableRequest;
use App\Services\Customer\CustomerServiceInterface;
use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Enums\StorageFolderEnum;
use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->customerService = app(CustomerServiceInterface::class);
});

it('Customer/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new CustomerDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'email',
            'phone',
            'postal_code',
            'code',
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
    $data = $this->customerService->paginate($params);
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

it('Customer/Paginate - Test the filter feature', function () {
    $datatableRequest = new CustomerDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'email',
            'phone',
            'postal_code',
            'code',
            'address'
        ],
        [
            'searchFields' => [
                'code' => 'CUS0001'
            ],
            'orders' => [
                'code' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->customerService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    //check data by code
    $this->assertTrue($data['data'][0]['code'] == 'CUS0001');
});

it('Customer/Paginate - Test the search feature', function () {
    $datatableRequest = new CustomerDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'email',
            'phone',
            'postal_code',
            'code',
            'address'
        ],
        [
            'globalSearch' => 'Customer 1',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->customerService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $customers = $data->jsonSerialize();
    $expect2 = false;
    foreach ($customers['data'] as $customer) {
        if (
            preg_match('/Customer 1/', strval(implode(' ', [$customer['first_name'], $customer['last_name']]))) ||
            preg_match('/Customer 1/', strval($customer['code'])) ||
            preg_match('/Customer 1/', strval(implode(', ', [
                $customer['detail_address'],
                $customer['city'],
                $customer['province'],
                $customer['country']
            ])))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

it('Customer/Create - Check create feature', function () {
    $file = UploadedFile::fake()->image('test.jpg', 600, 600)->size(1024);
    $dataCreate = [
        'first_name' => 'Customer',
        'last_name' => '01',
        'email' => 'customertest1@gmail.com',
        'phone' => "+12505550190",
        'postal_code' => 1000,
        'country' => 'Canada',
        'province' => 'British Columbia',
        'city' => 'Grande Prairie',
        'detail_address' => 'Prairie',
        'company_email' => 'company.example@gmail.com',
        'avatar' => $file
    ];
    $customer = $this->customerService->create($dataCreate);
    //check data type
    $this->assertInstanceOf(Customer::class, $customer);
    // check data return
    $this->assertEquals($customer->first_name, 'Customer');
    $this->assertEquals($customer->last_name, '01');
    $customerDatabase = Customer::where('first_name', 'Customer')
                                ->where('last_name', '01')
                                ->first();
    $this->assertNotNull($customerDatabase);
    //check avatar
    Storage::disk('public')->assertExists(StorageFolderEnum::CUSTOMER . '/' . $customer->avatar);
    //check log
    $this->assertEquals(ActionLogEnum::CREATED, $customerDatabase->activities->first()->event);
});

it('Customer/Show - Check customer not found', function () {
    expect(fn () => $this->customerService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('Customer/Show - Check success', function () {
    $customerDatabase = Customer::find(1);
    $customerService = $this->customerService->findById(1);
    $this->assertEquals($customerDatabase, $customerService);
});

it('Customer/Update - Check customer not found', function () {
    $dataUpdate = [
        'first_name' => 'Customer',
        'last_name' => '01',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'email' => 'customertest2@gmail.com',
        'phone' => '+12505550199',
        'postal_code' => 1000,
        'company_email' => 'companytest2@gmail.com',
    ];
    expect(fn () => $this->customerService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

//update
it('Customer/Update - Check update feature', function () {
    $customerDatabase = Customer::find(3);
    $dataUpdate = [
        'first_name' => 'Customer',
        'last_name' => '01',
        'country' => 'Canada',
        'detail_address' => 'British Columbia',
        'city' => 'Grande Prairie',
        'province' => 'British Columbia',
        'email' => 'customertest3@gmail.com',
        'phone' => "+12505550199",
        'postal_code' => 1000,
        'company_email' => 'companytest3@gmail.com',
    ];
    $customer = $this->customerService->update($dataUpdate, $customerDatabase->id);
    // check data type
    $this->assertInstanceOf(Customer::class, $customer);
    $customerInDatabase = Customer::find($customerDatabase->id);
    // check data in database
    $customerArray = $customerInDatabase->toArray();
    $this->assertEquals($dataUpdate, array_intersect_assoc($customerArray, $dataUpdate));
    //check log
    $log = $customer->activities()->orderBy('id', 'desc')->first();
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
            $this->assertEquals($customerDatabase[$key], $dataBeforeChanged[$key]);
        }
    }
});

it('Customer/Delete - Check customer not found', function () {
    expect(fn () => $this->customerService->delete(100000))->toThrow(ModelNotFoundException::class);
});

it('Customer/Delete - Check success', function () {
    $this->assertTrue($this->customerService->delete(2));
    $customer = Customer::find(2);
    $this->assertNull($customer);
    //check log and check soft delete
    $customer = Customer::withTrashed()->find(2);
    $this->assertNotNull($customer);
    $this->assertEquals(ActionLogEnum::DELETED, $customer->activities->first()->event);
});
