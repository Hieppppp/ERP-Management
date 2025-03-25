<?php

use App\Common\Entity\DatatableParams;
use App\Enums\ReturnOrderStatusEnum;
use App\Exceptions\ReturnOrderModificationException;
use App\Http\Requests\PurchaseOrder\ReceiptDatatableRequest;
use App\Jobs\SendReturnOrderMailJob;
use App\Mail\ReturnOrderMail;
use App\Models\ProductLocation;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderDetails;
use App\Services\ReturnOrder\ReturnOrderServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->returnOrderService = app(ReturnOrderServiceInterface::class);
});

it('ReturnOrder/Create - Check create feature', function () {
    $createData = [
        'purchase_order_id' => 5,
        'scheduled_date' => date('Y-m-d'),
        'products' => [
            [
                'product_location_id' => 1,
                'demand_quantity' => 10,
            ]
        ]
    ];
    $returnOrder = $this->returnOrderService->create($createData);
    // check data type
    $this->assertInstanceOf(ReturnOrder::class, $returnOrder);
    // check data return
    $this->assertEquals(date('Y-m-d', strtotime($returnOrder->scheduled_date)), date('Y-m-d'));
    // check data in database
    $returnOrderDatabase = ReturnOrder::where('id', $returnOrder->id)->first();
    $this->assertEquals($returnOrderDatabase->code, $returnOrder->code);
    $this->assertNotNull($returnOrderDatabase);
    $this->assertEquals($returnOrder->returnOrderDetails->toArray(), $returnOrderDatabase->returnOrderDetails->toArray());
});

it('ReturnOrder/Update - Check ReturnOrder not found', function () {
    $dataUpdate = [
        'scheduled_date' => date('Y-m-d')
    ];
    expect(fn() => $this->returnOrderService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('ReturnOrder/Update - Check ReturnOrder update status != READY', function () {
    $data = [
        'status' => ReturnOrderStatusEnum::CANCEL,
    ];
    expect(fn() => $this->returnOrderService->update($data, 3))->toThrow(ReturnOrderModificationException::class);
});

it('ReturnOrder/Update - Check update feature', function () {
    $data = [
        'status' => ReturnOrderStatusEnum::DONE,
        'scheduled_date' => date('Y-m-d', strtotime('+10 day', strtotime(date('Y-m-d')))),
        'products' => [
            [
                'return_order_detail_id' => 1,
                'quantity' => 10,
            ],
            [
                'return_order_detail_id' => 2,
                'quantity' => 10,
            ],
            [
                'return_order_detail_id' => 3,
                'quantity' => 10,
            ]
        ]
    ];
    $returnOrder = $this->returnOrderService->update($data, 1);

    // check data type
    $this->assertInstanceOf(ReturnOrder::class, $returnOrder);
    // check data in database
    $returnOrderInDatabase = ReturnOrder::find(1);
    $this->assertEquals(
        date('Y-m-d', strtotime($returnOrder->scheduled_date)),
        date('Y-m-d', strtotime($returnOrderInDatabase->scheduled_date))
    );
    $this->assertEquals($returnOrder->returnOrderDetails->toArray(), $returnOrderInDatabase->returnOrderDetails->toArray());
    // Check return order details updated
    $returnOrderDetail = ReturnOrderDetails::where('return_order_id', 1)->first();
    $this->assertEquals($returnOrderDetail->quantity, 10);
    // Check quantity of product location reduce successed
    $productLocation = ProductLocation::find($returnOrderDetail->product_location_id);
    $this->assertEquals($productLocation->quantity, 0);
});

it('ReturnOrder/getReceiptOut - Check the function runs successfully and check order by', function () {
    $datatableRequest = new ReceiptDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'created_at',
            'supplier_name',
            'quantity_demand',
            'quantity_returned',
            'shipping_address',
            'status'
        ],
        [
            'orders' => [
                'code' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->returnOrderService->receiptOut($params);
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
        return $a['code'] <=> $b['code'];
    });
    $this->assertEquals($sortedData, $data['data']);

    //check total data
    $this->assertLessThanOrEqual(5, count($data['data']));
});

it('ReturnOrder/getReceiptOut - Test the search feature', function () {
    $datatableRequest = new ReceiptDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'created_at',
            'supplier_name',
            'quantity_demand',
            'quantity_returned',
            'shipping_address',
            'status'
        ],
        [
            'searchFields' => [
                'code' => 'P00005'
            ],
            'orders' => [
                'code' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->returnOrderService->receiptOut($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue(preg_match('/P00005/', strval($data['data'][0]['code'])) ? true : false);
});

it('ReturnOrder/getReceiptOut - Test the filter feature', function () {
    $datatableRequest = new ReceiptDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'created_at',
            'supplier_name',
            'quantity_demand',
            'quantity_returned',
            'shipping_address',
            'status'
        ],
        [
            'globalSearch' => 'P00005',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->returnOrderService->receiptOut($params);
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
            preg_match('/P00005/', strval($purchaseOrder['code'])) ||
            preg_match('/P00005/', strval($purchaseOrder['supplier_name'])) ||
            preg_match('/P00005/', strval($purchaseOrder['shipping_address']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

it('ReturnOrder/sendMail sends an order shipped email', function () {
    $data = [
        'purchase_order_id' => 5,
        'scheduled_date' => date('Y-m-d'),
        'products' => [
            [
                'product_location_id' => 1,
                'demand_quantity' => 10,
            ]
        ]
    ];
    Mail::fake();
    $returnOrder = $this->returnOrderService->create($data);
    $dataMail = $this->returnOrderService->getReturnOrder($returnOrder->id);
    $dataMail['title'] = __('translation.returnOrder.returnOder');
    Mail::assertSent(ReturnOrderMail::class, function ($mail) use ($dataMail) {
        $mail->build();
        expect($mail->subject)->toContain(__('translation.returnOrder.newReturnOrder'));
        return $mail->returnOrder = $dataMail;
    });
});

it('pushes order shipped email job to the queue', function () {
    Queue::fake();
    $dataMail = $this->returnOrderService->getReturnOrder(1);
    $dataMail['title'] = __('translation.returnOrder.returnOrder');
    SendReturnOrderMailJob::dispatch($dataMail);
    Queue::assertPushed(SendReturnOrderMailJob::class, function ($job) use ($dataMail) {
        return $job->returnOrder === $dataMail;
    });
});
