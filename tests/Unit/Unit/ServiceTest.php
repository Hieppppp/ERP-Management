<?php

use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Http\Requests\Unit\UnitDatatableRequest;
use App\Models\ActivityLogs;
use App\Models\Unit;
use App\Services\Unit\UnitServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->unitService = app(UnitServiceInterface::class);
});

it('Unit/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new UnitDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'symbol',
            'description'
        ],
        [
            'orders' => [
                'id' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->unitService->paginate($params);
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
        return $a['id'] <=> $b['id'];
    });
    $this->assertEquals($sortedData, $data['data']);
});

it('Unit/Paginate - Test the filter feature', function () {
    $datatableRequest = new UnitDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'symbol',
            'description'
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
    $data = $this->unitService->paginate($params);
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

it('Unit/Paginate - Test the search feature', function () {
    $datatableRequest = new UnitDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'name',
            'symbol',
            'description'
        ],
        [
            'globalSearch' => 'Kilogram',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->unitService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $expect2 = false;
    foreach ($data['data'] as $unit) {
        if (
            preg_match('/Kilogram/', strval($unit['name'])) ||
            preg_match('/Kilogram/', strval($unit['id'])) ||
            preg_match('/Kilogram/', strval($unit['symbol'])) ||
            preg_match('/Kilogram/', strval($unit['description']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

it('Unit/Create - Check create feature', function () {
    $dataCreate = [
        'name' => 'milligram',
        'symbol' => 'mg',
    ];
    $unit = $this->unitService->create($dataCreate);
    // check data type
    $this->assertInstanceOf(Unit::class, $unit);
    // check data return
    $this->assertEquals($unit->name, 'milligram');
    $unitDatabase = Unit::where('name', 'milligram')->first();
    $this->assertNotNull($unitDatabase);

    //check log
    $this->assertEquals(ActionLogEnum::CREATED, $unitDatabase->activities->first()->event);
});

it('Unit/Show - Check tax not found', function () {
    expect(fn () => $this->unitService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('Unit/Show - Check success', function () {
    $unitDatabase = Unit::find(2);
    $unitService = $this->unitService->findById(2);
    $this->assertEquals($unitDatabase, $unitService);
});

it('Unit/Update - Check Unit not found', function () {
    $dataUpdate = [
        'name' => 'meter',
        'symbol' => 'm'
    ];
    expect(fn () => $this->unitService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('Unit/Update - Check update feature', function () {
    $dataUpdate = [
        'name' => 'Kilogram',
        'symbol' => 'kg',
        'description' => 'Kilogram is the base unit of mass in the International System of Units'
    ];
    $unit = $this->unitService->update($dataUpdate, 1);
    $this->assertInstanceOf(Unit::class, $unit);
    $unitInDatabase = Unit::find(1);
    // check data in database
    $this->assertEquals($unit->name, $unitInDatabase->name);
    $this->assertEquals($unit->symbol, $unitInDatabase->symbol);
    //check log
    $this->assertEquals(ActionLogEnum::UPDATED, $unitInDatabase->activities->first()->event);
});
it('Unit/Delete - Check tax not found', function () {
    expect(fn () => $this->unitService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('Unit/Delete - Check success', function () {
    $this->assertTrue($this->unitService->delete(2));
    $unit = Unit::find(2);
    $this->assertNull($unit);

    //check log and check soft delete
    $unit = Unit::withTrashed()->find(2);
    $this->assertNotNull($unit);
    $this->assertEquals(ActionLogEnum::DELETED, $unit->activities->first()->event);
});
