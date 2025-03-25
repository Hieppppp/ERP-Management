<?php

use App\Common\Entity\DatatableParams;
use App\Http\Requests\Batch\BatchDatatableRequest;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

beforeEach(function () {
    $this->purchaseOrderService = app(PurchaseOrderServiceInterface::class);
    $this->purchaseOrderService->receiveProduct([
        'products' => [
            [
                'id' => 1,
                'received_quantity' => 10,
                'sku' => 'sku123'
            ],
            [
                'id' => 2,
                'received_quantity' => 10,
                'sku' => 'sku1234'
            ]
        ]
    ], 2);
    $this->purchaseOrderService->putProductOnShelf(3, 3, [
        'shelves' => [
            [
                'id' => 1,
                'quantity' => 15
            ]
        ]
    ]);
});


it('Batch/getListBatch - Check the function runs successfully and check order by', function () {
    $datatableRequest = new BatchDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'received_date',
            'supplier_name',
            'number_of_products',
            'storage_location'
        ],
        [
            'orders' => [
                'code' => 'asc'
            ],
            'length' => 3
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->getListBatch($params);
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
        return $a['batchCode'] <=> $b['batchCode'];
    });
    $this->assertEquals($sortedData, $data['data']);
    //check total data
    $this->assertLessThanOrEqual(3, count($data['data']));
});

it('Batch/Paginate - Test the filter feature', function () {
    $datatableRequest = new BatchDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'received_date',
            'supplier_name',
            'number_of_products',
            'storage_location'
        ],
        [
            'searchFields' => [
                'code' => 'BATCH0003'
            ],
            'orders' => [
                'id' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->getListBatch($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue($data['data'][0]['batchCode'] == 'BATCH0003');
});

it('Batch/Paginate - Test the search feature', function () {
    $datatableRequest = new BatchDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'received_date',
            'supplier_name',
            'number_of_products',
            'storage_location'
        ],
        [
            'globalSearch' => 'BATCH0003',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->getListBatch($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $categories = $data->jsonSerialize();
    $expect2 = false;
    foreach ($categories['data'] as $purchaseOrder) {
        if (
            preg_match('/BATCH0003/', strval($purchaseOrder['batchCode'])) ||
            preg_match('/BATCH0003/', strval($purchaseOrder['received_date'])) ||
            preg_match('/BATCH0003/', strval($purchaseOrder['supplier_name'])) ||
            preg_match('/BATCH0003/', strval($purchaseOrder['number_of_products'])) ||
            preg_match('/BATCH0003/', strval($purchaseOrder['storage_location']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});
