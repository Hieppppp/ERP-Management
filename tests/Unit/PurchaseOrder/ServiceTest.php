<?php

use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Enums\PurchaseOrderStatusEnum;
use App\Exceptions\PurchaseOrderModificationException;
use App\Http\Requests\PurchaseOrder\PurchaseOrderDatatableRequest;
use App\Jobs\SendPurchaseOrderEmailJob;
use App\Models\ProductPurchaseOrder;
use App\Models\ProductSupplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProductShelve;
use App\Services\PurchaseOrder\PurchaseOrderServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseOrderEmail;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->purchaseOrderService = app(PurchaseOrderServiceInterface::class);
});

it('PurchaseOrder/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new PurchaseOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'created_at',
            'supplier_name',
            'total_amount',
            'scheduled_date',
            'warehouse_name',
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
    $data = $this->purchaseOrderService->paginate($params);
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

it('PurchaseOrder/Paginate - Test the filter feature', function () {
    $datatableRequest = new PurchaseOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'created_at',
            'supplier_name',
            'total_amount',
            'scheduled_date',
            'warehouse_name',
            'status'
        ],
        [
            'searchFields' => [
                'code' => 'P00001'
            ],
            'orders' => [
                'id' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue($data['data'][0]['code'] == 'P00001');
});

it('PurchaseOrder/Paginate - Test the search feature', function () {
    $datatableRequest = new PurchaseOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'created_at',
            'supplier_name',
            'total_amount',
            'scheduled_date',
            'warehouse_name',
            'status'
        ],
        [
            'globalSearch' => 'P00001',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->paginate($params);
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
            preg_match('/P00001/', strval($purchaseOrder['code'])) ||
            preg_match('/P00001/', strval($purchaseOrder['created_at'])) ||
            preg_match('/P00001/', strval($purchaseOrder['supplier_name'])) ||
            preg_match('/P00001/', strval($purchaseOrder['warehouse_name'])) ||
            preg_match('/P00001/', strval($purchaseOrder['status'])) ||
            preg_match('/P00001/', strval($purchaseOrder['scheduled_date']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

it('PurchaseOrder/Create - Check create feature', function () {
    $dataCreate = [
        'supplier_id' => 1,
        'warehouse_id' => 1,
        'scheduled_date' => date('Y-m-d'),
        'products' => [
            [
                'id' => 1,
                'quantity' => 10,
                'unit_cost' => 10
            ],
            [
                'id' => 2,
                'quantity' => 15,
                'unit_cost' => 10
            ],
            [
                'id' => 3,
                'quantity' => 20,
                'unit_cost' => 10
            ]
        ]
    ];
    $purchaseOrder = $this->purchaseOrderService->create($dataCreate);
    // check data type
    $this->assertInstanceOf(PurchaseOrder::class, $purchaseOrder);
    // check data return
    $this->assertEquals(date('Y-m-d', strtotime($purchaseOrder->scheduled_date)), date('Y-m-d'));
    // check data in database
    $purchaseOrderDatabase = PurchaseOrder::where('id', $purchaseOrder->id)->first();
    $this->assertNotNull($purchaseOrderDatabase);
    $this->assertEquals($purchaseOrder->products->toArray(), $purchaseOrderDatabase->products->toArray());
});

it('PurchaseOrder/Show - Check PurchaseOrder not found', function () {
    expect(fn() => $this->purchaseOrderService->findById(10000))->toThrow(ModelNotFoundException::class);
});

it('PurchaseOrder/Show - Check success', function () {
    $purchaseOrderDatabase = PurchaseOrder::find(2);
    $purchaseOrderService = $this->purchaseOrderService->findById(2);
    $this->assertEquals($purchaseOrderDatabase, $purchaseOrderService);
});

it('PurchaseOrder/Update - Check PurchaseOrder not found', function () {
    $dataUpdate = [
        'supplier_id' => 12,
    ];
    expect(fn() => $this->purchaseOrderService->update($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('PurchaseOrder/Update - Check purchase order cannot be updated due to status', function () {
    $dataUpdate = [
        'supplier_id' => 12,
    ];
    expect(fn() => $this->purchaseOrderService->update($dataUpdate, 3))->toThrow(PurchaseOrderModificationException::class);
});

it('PurchaseOrder/Update - Check update feature', function () {
    $dataUpdate = [
        'supplier_id' => 2,
        'scheduled_date' => date('Y-m-d 00:00:00', strtotime('+10 day', strtotime(date('Y-m-d')))),
        'products' => [
            [
                'id' => 1,
                'quantity' => 10,
                'unit_cost' => 10
            ]
        ]
    ];
    $purchaseOrder = $this->purchaseOrderService->update($dataUpdate, 1);

    // check data type
    $this->assertInstanceOf(PurchaseOrder::class, $purchaseOrder);
    // check data in database
    $purchaseOrderInDatabase = PurchaseOrder::find(1);
    $this->assertEquals($purchaseOrder->scheduled_date, $purchaseOrderInDatabase->scheduled_date);
    $this->assertEquals($purchaseOrder->products->toArray(), $purchaseOrderInDatabase->products->toArray());
});

it('PurchaseOrder/Delete - Check PurchaseOrder not found', function () {
    expect(fn() => $this->purchaseOrderService->delete(10000))->toThrow(ModelNotFoundException::class);
});

it('PurchaseOrder/Delete - Check purchase order cannot be deleted due to status', function () {
    expect(fn() => $this->purchaseOrderService->delete(3))->toThrow(PurchaseOrderModificationException::class);
});

it('PurchaseOrder/Delete - Check success', function () {
    $this->assertTrue($this->purchaseOrderService->delete(1));
    $purchaseOrder = PurchaseOrder::find(1);
    $this->assertNull($purchaseOrder);
});

it('PurchaseOrder/sendPurchaseOrder - Check PurchaseOrder not found', function () {
    expect(fn() => $this->purchaseOrderService->sendPurchaseOrder(10000))->toThrow(ModelNotFoundException::class);
});

it('PurchaseOrder/sendPurchaseOrder - Check PurchaseOrder not send when status != Draft', function () {
    expect(fn() => $this->purchaseOrderService->sendPurchaseOrder(2))->toThrow(PurchaseOrderModificationException::class);
});

it('PurchaseOrder/sendPurchaseOrder - Check PurchaseOrder send success', function () {
    $sendPurchaseOrder = $this->purchaseOrderService->sendPurchaseOrder(1);
    $this->assertTrue($sendPurchaseOrder);
    $productPurchaseOrder = ProductPurchaseOrder::where('product_id', 1)->where('purchase_order_id', 1)->first();
    $productSupplier = ProductSupplier::where('product_id', 1)->where('supplier_id', 1)->first();
    $this->assertEquals($productPurchaseOrder->unit_cost, $productSupplier->unit_cost);
    $purchaseOrder = PurchaseOrder::find(1);
    $this->assertEquals($purchaseOrder->status, PurchaseOrderStatusEnum::PENDING);
});

it('PurchaseOrder/receiveProduct - Check PurchaseOrder not found', function () {
    expect(fn() => $this->purchaseOrderService->receiveProduct([], 10000))->toThrow(ModelNotFoundException::class);
});

it('PurchaseOrder/receiveProduct - Check PurchaseOrder not send when status != PENDING', function () {
    expect(fn() => $this->purchaseOrderService->receiveProduct([], 3))->toThrow(PurchaseOrderModificationException::class);
});

it('PurchaseOrder/receiveProduct - Check PurchaseOrder receive success', function () {
    $receivePurchaseOrder = $this->purchaseOrderService->receiveProduct([
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
    $this->assertTrue($receivePurchaseOrder);
    $productPurchaseOrders = ProductPurchaseOrder::where('purchase_order_id', 2)->get();
    foreach ($productPurchaseOrders as $productPurchaseOrder) {
        $this->assertEquals($productPurchaseOrder->received_quantity, 10);
    }
    $purchaseOrder = PurchaseOrder::find(2);
    $this->assertEquals($purchaseOrder->status, PurchaseOrderStatusEnum::PENDING_SHELVE);
});

it('PurchaseOrder/cancelPurchaseOrder - Check PurchaseOrder not found', function () {
    expect(fn() => $this->purchaseOrderService->cancelPurchaseOrder(10000))->toThrow(ModelNotFoundException::class);
});

it('PurchaseOrder/cancelPurchaseOrder - Check PurchaseOrder not cancel when status != PENDING', function () {
    expect(fn() => $this->purchaseOrderService->cancelPurchaseOrder(3))->toThrow(PurchaseOrderModificationException::class);
});

it('PurchaseOrder/cancelPurchaseOrder - Check PurchaseOrder Cancel Success', function () {
    $cancelPurchaseOrder = $this->purchaseOrderService->cancelPurchaseOrder(2);
    $this->assertTrue($cancelPurchaseOrder);
    $purchaseOrder = PurchaseOrder::find(2);
    $this->assertEquals($purchaseOrder->status, PurchaseOrderStatusEnum::CANCEL);
});

it('PurchaseOrder/putProductOnShelf - Check PurchaseOrder not found', function () {
    expect(fn() => $this->purchaseOrderService->putProductOnShelf(100000, 10000, []))->toThrow(ModelNotFoundException::class);
});
it('PurchaseOrder/putProductOnShelf - Check PurchaseOrder not put when status != PENDING_SHELVE', function () {
    expect(fn() => $this->purchaseOrderService->putProductOnShelf(2, 3, []))->toThrow(PurchaseOrderModificationException::class);
});

it('PurchaseOrder/putProductOnShelf - Check PurchaseOrder put success', function () {
    $purchaseOrder = $this->purchaseOrderService->putProductOnShelf(3, 3, [
        'shelves' => [
            [
                'id' => 1,
                'quantity' => 15
            ]
        ]
    ]);
    $this->assertTrue($purchaseOrder);
    $purchaseProductShelve = PurchaseProductShelve::where('purchase_order_id', 3)->where('product_id', 3)->where('shelve_id', 1)->first();
    $this->assertNotNull($purchaseProductShelve);
});

it('PurchaseOrder/finishPurchaseOrder - Check PurchaseOrder Done Success', function () {
    $finishPurchaseOrder = $this->purchaseOrderService->finishPurchaseOrder(3);
    $this->assertTrue($finishPurchaseOrder);
    $purchaseOrder = PurchaseOrder::find(3);
    $this->assertEquals($purchaseOrder->status, PurchaseOrderStatusEnum::DONE);
});

it('PurchaseOrder/getTotalByStatus - Check total purchase order by status', function () {
    $purchaseOrder = $this->purchaseOrderService->getTotalByStatus();
    $totalDraft = PurchaseOrder::where('status', PurchaseOrderStatusEnum::DRAFT)->count();
    $totalPending = PurchaseOrder::where('status', PurchaseOrderStatusEnum::PENDING)->count();
    $totalPendingShelve = PurchaseOrder::where('status', PurchaseOrderStatusEnum::PENDING_SHELVE)->count();
    $totalDone = PurchaseOrder::where('status', PurchaseOrderStatusEnum::DONE)->count();
    $totalCancel = PurchaseOrder::where('status', PurchaseOrderStatusEnum::CANCEL)->count();
    $this->assertEquals($purchaseOrder[PurchaseOrderStatusEnum::DRAFT]['total'] ?? 0, $totalDraft);
    $this->assertEquals($purchaseOrder[PurchaseOrderStatusEnum::PENDING]['total'] ?? 0, $totalPending);
    $this->assertEquals($purchaseOrder[PurchaseOrderStatusEnum::PENDING_SHELVE]['total'] ?? 0, $totalPendingShelve);
    $this->assertEquals($purchaseOrder[PurchaseOrderStatusEnum::DONE]['total'] ?? 0, $totalDone);
    $this->assertEquals($purchaseOrder[PurchaseOrderStatusEnum::CANCEL]['total'] ?? 0, $totalCancel);
});

it('PurchaseOrder/getActivityPurchaseOrder - Check Create Purchase Order', function () {
    $dataCreate = [
        'supplier_id' => 1,
        'warehouse_id' => 1,
        'scheduled_date' => date('Y-m-d'),
        'products' => [
            [
                'id' => 1,
                'quantity' => 10,
                'unit_cost' => 10
            ],
            [
                'id' => 2,
                'quantity' => 15,
                'unit_cost' => 10
            ],
            [
                'id' => 3,
                'quantity' => 20,
                'unit_cost' => 10
            ]
        ]
    ];
    $purchaseOrder = $this->purchaseOrderService->create($dataCreate);
    $log = $this->purchaseOrderService->getActivityPurchaseOrder($purchaseOrder->id);
    $this->assertEquals($log[0]['event'], ActionLogEnum::CREATED);
});

it('PurchaseOrder/getActivityPurchaseOrder - Check Update Purchase Order', function () {
    $dataUpdate = [
        'supplier_id' => 2,
        'scheduled_date' => date('Y-m-d 00:00:00', strtotime('+10 day', strtotime(date('Y-m-d')))),
        'products' => [
            [
                'id' => 1,
                'quantity' => 20,
                'unit_cost' => 10
            ],
            [
                'id' => 4,
                'quantity' => 40,
                'unit_cost' => 20
            ]
        ]
    ];
    $purchaseOrder = $this->purchaseOrderService->update($dataUpdate, 1);
    $log = $this->purchaseOrderService->getActivityPurchaseOrder(1);
    $attributes = $log[0]['properties']['attributes'] ?? [];
    $oldValue = $log[0]['properties']['old'] ?? [];
    //check supplier
    $this->assertEquals($attributes['supplier_id'], 2);
    $this->assertEquals($oldValue['supplier_id'], 1);
    //check scheduled_date
    $this->assertEquals($attributes['scheduled_date'], date('Y-m-d 00:00:00', strtotime('+10 day', strtotime(date('Y-m-d')))));
    $this->assertEquals($oldValue['scheduled_date'], Carbon::parse('2024-06-27')->format("Y-m-d H:i:s"));
    //check products
    $this->assertNotEquals($log[0]['productDifference'], []);
    $this->assertNotEquals($log[0]['productDifference']['update'], []);
    $this->assertNotEquals($log[0]['productDifference']['create'], []);
    $this->assertNotEquals($log[0]['productDifference']['delete'], []);

    $this->assertEquals($log[0]['productDifference']['update'][0], [
        "id" => 1,
        "name" => "iPhone",
        "old_quantity" => 10,
        "new_quantity" => 20
    ]);

    $this->assertEquals($log[0]['productDifference']['create'][0]['id'], 4);
    $this->assertEquals($log[0]['productDifference']['delete'][0]['id'], 2);
    $this->assertEquals($log[0]['productDifference']['delete'][1]['id'], 3);
});

it('PurchaseOrder/getActivityPurchaseOrder - Check send Purchase Order', function () {
    $sendPurchaseOrder = $this->purchaseOrderService->sendPurchaseOrder(1);
    $log = $this->purchaseOrderService->getActivityPurchaseOrder(1);
    $this->assertEquals($log[0]['event'], ActionLogEnum::SENDED);
});

it('PurchaseOrder/getActivityPurchaseOrder - Check receive Purchase Order', function () {
    $sendPurchaseOrder = $this->purchaseOrderService->receiveProduct([
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
    $log = $this->purchaseOrderService->getActivityPurchaseOrder(2);
    $this->assertEquals($log[0]['event'], ActionLogEnum::RECEIVED);
});

it('PurchaseOrder/getActivityPurchaseOrder - Check cancel Purchase Order', function () {
    $this->purchaseOrderService->cancelPurchaseOrder(2);
    $log = $this->purchaseOrderService->getActivityPurchaseOrder(2);
    $this->assertEquals($log[0]['event'], ActionLogEnum::CANCELED);
});

it('PurchaseOrder/getActivityPurchaseOrder - Check finish Purchase Order', function () {
    $this->purchaseOrderService->finishPurchaseOrder(3);
    $log = $this->purchaseOrderService->getActivityPurchaseOrder(3);
    $this->assertEquals($log[0]['event'], ActionLogEnum::DONE);
});

it('PurchaseOrder/getReceiptIn - Check the function runs successfully and check order by', function () {
    $datatableRequest = new PurchaseOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'receipt_code',
            'created_at',
            'supplier_name',
            'quantity_demand',
            'quantity_received',
            'shipping_address',
            'status'
        ],
        [
            'orders' => [
                'receipt_code' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->getReceiptIn($params);
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
        return $a['receipt_code'] <=> $b['receipt_code'];
    });
    $this->assertEquals($sortedData, $data['data']);


    //check total data
    $this->assertLessThanOrEqual(5, count($data['data']));
});

it('PurchaseOrder/getReceiptIn - Test the search feature', function () {
    $datatableRequest = new PurchaseOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'receipt_code',
            'created_at',
            'supplier_name',
            'quantity_demand',
            'quantity_received',
            'shipping_address',
            'status'
        ],
        [
            'searchFields' => [
                'receipt_code' => 'P00003'
            ],
            'orders' => [
                'receipt_code' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->getReceiptIn($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue(preg_match('/P00003/', strval($data['data'][0]['receipt_code'])) ? true : false);
});

it('PurchaseOrder/getReceiptIn - Test the filter feature', function () {
    $datatableRequest = new PurchaseOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'receipt_code',
            'created_at',
            'supplier_name',
            'quantity_demand',
            'quantity_received',
            'shipping_address',
            'status'
        ],
        [
            'globalSearch' => 'P00003',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->purchaseOrderService->getReceiptIn($params);
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
            preg_match('/P00003/', strval($purchaseOrder['receipt_code'])) ||
            preg_match('/P00003/', strval($purchaseOrder['supplier_name'])) ||
            preg_match('/P00003/', strval($purchaseOrder['shipping_address']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

it('PurchaseOrder/getPurchaseOrderProduct - Test get purchase order product for done purchase order', function () {
    $data = $this->purchaseOrderService->getPurchaseOrderProduct(5);

    $this->assertInstanceOf(Collection::class, $data);
    $this->assertEquals(count($data), 3);
    $this->assertEquals($data[0]['returned_quantity'], 5);
});

it('PurchaseOrder/getPurchaseOrderProduct - Test get purchase order product for pending shelve purchase order', function () {
    $data = $this->purchaseOrderService->getPurchaseOrderProduct(3);

    $this->assertInstanceOf(Collection::class, $data);
    $this->assertEquals(count($data), 3);
    $this->assertEquals($data[0]['received_quantity'], 10);
    $this->assertEquals($data[0]['returned_quantity'], 0);
});

it('PurchaseOrder/getPurchaseOrderProduct - Test get purchase order product for pending purchase order', function () {
    $data = $this->purchaseOrderService->getPurchaseOrderProduct(2);

    $this->assertInstanceOf(Collection::class, $data);
    $this->assertEquals(count($data), 3);
    $this->assertEquals($data[0]['quantity'], 10);
    $this->assertEquals($data[0]['received_quantity'], null);
});

it('PurchaseOrder/sendMail sends an order shipped email', function () {
    Mail::fake();
    $this->purchaseOrderService->sendPurchaseOrder(1);
    $dataMail = $this->purchaseOrderService->getDetailPurchaseOrder(1);
    $dataMail['title'] = __('translation.purchaseOrder.purchaseOrder');
    Mail::assertSent(PurchaseOrderEmail::class, function ($mail) use ($dataMail) {
        $mail->build();
        expect($mail->subject)->toContain(__('translation.purchaseOrder.newPurchaseOrder'));
        return $mail->purchaseOrder = $dataMail;
    });
});

it('pushes order shipped email job to the queue', function () {
    Queue::fake();
    $dataMail = $this->purchaseOrderService->getDetailPurchaseOrder(1);
    $dataMail['title'] = __('translation.purchaseOrder.purchaseOrder');
    SendPurchaseOrderEmailJob::dispatch($dataMail);
    Queue::assertPushed(SendPurchaseOrderEmailJob::class, function ($job) use ($dataMail) {
        return $job->purchaseOrder === $dataMail;
    });
});
