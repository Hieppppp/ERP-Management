<?php

use App\Common\Entity\DatatableParams;
use App\Http\Requests\Log\LogDatatableRequest;
use App\Services\Log\LogServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->logService = app(LogServiceInterface::class);
});

it('Log/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new LogDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'created_at',
            'module',
            'user',
            'action',
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
    $data = $this->logService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    //check order by
    $sortedData = $data['data'];
    usort($sortedData, function ($a, $b) {
        return $a['id'] <=> $b['id'];
    });
    $this->assertEquals($sortedData, $data['data']);
    //check total data
    $this->assertLessThanOrEqual(5, count($data['data']));
});

it('Log/Paginate - Test the filter feature', function () {
    $datatableRequest = new LogDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'created_at',
            'module',
            'user',
            'action',
            'description'
        ],
        [
            'searchFields' => [
                'module' => 'App\\Models\\User'
            ],
            'orders' => [
                'id' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->logService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    foreach ($data['data'] as $log) {
        $this->assertTrue($log['module'] == 'App\\Models\\User');
    }
});

it('Log/Paginate - Test the search feature', function () {
    $datatableRequest = new LogDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'id',
            'created_at',
            'module',
            'user',
            'action',
            'description'
        ],
        [
            'globalSearch' => '1',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->logService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $logs = $data->jsonSerialize();
    if ($logs['data']) {
        $expect2 = false;
        foreach ($logs['data'] as $log) {
            if (
                preg_match('/1/', strval($log['id'])) ||
                preg_match('/1/', strval($log['user']))
            ) {
                $expect2 = true;
                break;
            }
        }
        //check data by name
        $this->assertTrue($expect2);
    }
});
