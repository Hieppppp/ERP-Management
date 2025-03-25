<?php

use App\Common\Entity\DatatableParams;
use App\Enums\DeliverMethodEnum;
use App\Enums\PaymentTermTypeEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\SaleOrderReceiptStatusEnum;
use App\Enums\SaleOrderStatusEnum;
use App\Http\Requests\SaleOrder\InvoiceDatatable;
use App\Http\Requests\SaleOrder\SaleOrderDatatableRequest;
use App\Jobs\SendInvoiceJob;
use App\Mail\InvoiceMail;
use App\Models\RegisterPayment;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Models\SaleOrderLocation;
use App\Services\SaleOrder\SaleOrderServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->saleOrderService = app(SaleOrderServiceInterface::class);
});

it('Invoice/Paginate - Check list invoice feature', function () {
    $datatableRequest = new InvoiceDatatable();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'customer_name',
            'created_at',
            'payment_term',
            'total_amount',
            'payment_status'
        ],
        [
            'orders' => [
                'code' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->saleOrderService->getInvoiceList($params);
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

it('Invoice/Paginate - Test the filter feature', function () {
    $datatableRequest = new InvoiceDatatable();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'customer_name',
            'created_at',
            'payment_term',
            'total_amount',
            'payment_status'
        ],
        [
            'searchFields' => [
                'code' => 'INV0002'
            ],
            'orders' => [
                'code' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->saleOrderService->getInvoiceList($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue($data['data'][0]['invoice_code'] == 'INV0002');
});

it('Invoice/Paginate - Test the search feature', function () {
    $datatableRequest = new InvoiceDatatable();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'customer_name',
            'created_at',
            'payment_term',
            'total_amount',
            'payment_status'
        ],
        [
            'globalSearch' => 'INV0002',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->saleOrderService->getInvoiceList($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $invoices = $data->jsonSerialize();
    $expect2 = false;
    foreach ($invoices['data'] as $invoice) {
        if (
            preg_match('/INV0002/', strval($invoice['invoice_code'])) ||
            preg_match('/INV0002/', strval($invoice['customer_name'])) ||
            preg_match('/INV0002/', strval($invoice['created_at'])) ||
            preg_match('/INV0002/', strval($invoice['payment_term'])) ||
            preg_match('/INV0002/', strval($invoice['total_amount']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by code
    $this->assertTrue($expect2);
});

it('Invoice/RegisterPayment - Check invoice not found', function () {
    $dataUpdate = [
        'date' => date('Y-m-d'),
    ];
    expect(fn() => $this->saleOrderService->registerPayment($dataUpdate, 10000))->toThrow(ModelNotFoundException::class);
});

it('Invoice/RegisterPayment - Check register success', function () {
    $data = [
        'payment_type' => PaymentTypeEnum::CHECK,
        'date' => date('Y-m-d'),
        'paid_amount' => 283.85
    ];
    $registerPayment = $this->saleOrderService->registerPayment($data, 2);
    // check data type
    $this->assertInstanceOf(RegisterPayment::class, $registerPayment);
    // check data return
    $this->assertEquals(date('Y-m-d', strtotime($registerPayment->date)), date('Y-m-d'));
    // check data in database
    $registerPaymentDatabase = RegisterPayment::where('id', $registerPayment->id)->first();
    $this->assertNotNull($registerPaymentDatabase);
    $this->assertEquals($registerPaymentDatabase->paid_amount, 283.85);
});

it('Invoice/getTotalAmount - Check invoice not found', function () {
    expect(fn() => $this->saleOrderService->getTotalAmount(10000))->toThrow(ModelNotFoundException::class);
});

it('Invoice/getTotalAmount - Check get total success', function () {
    $total = $this->saleOrderService->getTotalAmount(2);
    $saleOrder = $this->saleOrderService->findById(2);
    $totalPaid = $saleOrder->registerPayments->sum('paid_amount');
    $saleOrderDetail = SaleOrderDetail::where('sale_order_id', 2)->get();
    $totalAmount = 0;
    foreach ($saleOrderDetail as $detail) {
        $totalAmount += $detail->quantity * $detail->unit_price * (100 - $detail->discount_rate) / 100;
    }
    if ($saleOrder?->tax_rate) {
        $totalAmount = $totalAmount * (1 + $saleOrder->tax_rate / 100);
    }
    $this->assertEquals($totalAmount, $total['totalAmount']);
    $this->assertEquals($totalPaid, $total['totalPaid']);
});

it('SaleOrder/Create - Check create sale order feature', function () {
    $createData = [
        'customer_id' => '1',
        'customer_email' => 'customer1@gmail.com',
        'customer_phone' => '+12505550190',
        'deliver_address' => 'Số 20, Airdrie, Alberta, Canada',
        'customer_address' => 'Số 20, Airdrie, Alberta, Canada',
        'tax_data' => [
            [
                'code' => 'TPS',
                'rate' => 5,
            ]
        ],
        'order_status' => SaleOrderStatusEnum::DRAFT,
        'delivery_method' => DeliverMethodEnum::IN_STORE_PICKUP,
        'payment_term' => PaymentTermTypeEnum::IMMEDIATE_PAYMENT,
        'products' => [
            [
                'id' => 1,
                'discount_amount' => 10,
                'unit_price' => 15,
                'order_quantity' => 10
            ],
            [
                'id' => 2,
                'discount_amount' => 10,
                'unit_price' => 15,
                'order_quantity' => 5
            ],
        ],
        'note' => 'note'
    ];
    $saleOrder = $this->saleOrderService->create($createData);
    // check data type
    $this->assertInstanceOf(SaleOrder::class, $saleOrder);
    // check data in database
    $saleOrderDatabase = SaleOrder::where('id', $saleOrder->id)->first();
    $this->assertEquals($saleOrderDatabase->code, $saleOrder->code);
    $this->assertNotNull($saleOrderDatabase->products);
    $this->assertEquals($saleOrder->products->toArray(), $saleOrderDatabase->products->toArray());
});

it('SaleOrder/Update - Check update sale order feature', function () {
    $updateData = [
        'customer_id' => '1',
        'customer_email' => 'customer1@gmail.com',
        'customer_phone' => '+12505550190',
        'deliver_address' => 'Số 20, Airdrie, Alberta, Canada',
        'customer_address' => 'Số 20, Airdrie, Alberta, Canada',
        'tax_data' => [
            [
                'code' => 'TPS',
                'rate' => 5,
            ]
        ],
        'order_status' => SaleOrderStatusEnum::DRAFT,
        'delivery_method' => DeliverMethodEnum::IN_STORE_PICKUP,
        'payment_term' => PaymentTermTypeEnum::IMMEDIATE_PAYMENT,
        'products' => [
            [
                'id' => 2,
                'discount_amount' => 10,
                'unit_price' => 15,
                'order_quantity' => 10
            ],
            [
                'id' => 3,
                'discount_amount' => 10,
                'unit_price' => 15,
                'order_quantity' => 5
            ],
        ],
        'note' => 'note'
    ];
    $saleOrder = $this->saleOrderService->update($updateData, 2);
    // check data type
    $this->assertInstanceOf(SaleOrder::class, $saleOrder);
    // check data in database
    $saleOrderDatabase = SaleOrder::where('id', $saleOrder->id)->first();
    $this->assertEquals($saleOrderDatabase->code, $saleOrder->code);
    $this->assertNotNull($saleOrderDatabase->products);
    $this->assertEquals($saleOrder->products->toArray(), $saleOrderDatabase->products->toArray());
});

it('SaleOrder/updateStatus - Check update status sale order feature', function () {
    $updateData = [
        'order_status' => SaleOrderStatusEnum::IN_TRANSIT,
        'note' => 'note'
    ];
    $saleOrder = $this->saleOrderService->updateStatus($updateData, 2);
    // check data type
    $this->assertInstanceOf(SaleOrder::class, $saleOrder);
    // check data in database
    $saleOrderDatabase = SaleOrder::find($saleOrder->id);
    $this->assertEquals($saleOrderDatabase->order_status, $updateData['order_status']);
});

it('SaleOrder/updateStatus - Check update status sale order fail when ROG is not validate', function () {
    $updateData = [
        'order_status' => SaleOrderStatusEnum::IN_TRANSIT,
        'note' => 'note'
    ];
    $saleOrderNotValidateROG = SaleOrder::where('receipt_status', '!=', SaleOrderReceiptStatusEnum::DONE)->first();
    $saleOrder = $this->saleOrderService->updateStatus($updateData, $saleOrderNotValidateROG->id);
    $saleOrderAfterUpdate = SaleOrder::find($saleOrder->id);
    $this->assertNotEquals($saleOrderAfterUpdate->order_status, $updateData['order_status']);
});

it('SaleOrder/Delete - Check success delete draft sale order', function () {
    $draftSaleOrder = SaleOrder::where('order_status', SaleOrderStatusEnum::DRAFT)->first();
    $this->assertTrue($this->saleOrderService->delete($draftSaleOrder->id));
    $saleOrder = SaleOrder::find($draftSaleOrder->id);
    $this->assertNull($saleOrder);
});

it('SaleOrder/Delete - Check fail delete none draft sale order', function () {
    $draftSaleOrder = SaleOrder::where('order_status', '!=', SaleOrderStatusEnum::DRAFT)->first();
    $this->assertFalse($this->saleOrderService->delete($draftSaleOrder->id));
    $saleOrder = SaleOrder::find($draftSaleOrder->id);
    $this->assertNotNull($saleOrder);
});

it('SaleOrder/Paginate - Check the function runs successfully and check order by', function () {
    $datatableRequest = new SaleOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'customer_name',
            'created_at',
            'payment_term',
            'total_quantity',
            'total_amount',
            'deliver_address',
            'order_status',
        ],
        [
            'orders' => [
                'code' => 'asc'
            ],
            'length' => 5
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->saleOrderService->paginate($params);
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

it('SaleOrder/Paginate - Test the filter feature', function () {
    $datatableRequest = new SaleOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'customer_name',
            'created_at',
            'payment_term',
            'total_quantity',
            'total_amount',
            'deliver_address',
            'order_status',
        ],
        [
            'searchFields' => [
                'code' => 'S00002'
            ],
            'orders' => [
                'id' => 'asc'
            ],
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->saleOrderService->paginate($params);
    //check data type
    $this->assertThat(
        $data,
        $this->logicalOr(
            $this->isInstanceOf(LengthAwarePaginator::class),
            $this->isInstanceOf(Paginator::class)
        )
    );
    $data = $data->jsonSerialize();
    $this->assertTrue($data['data'][0]['code'] == 'S00002');
});

it('SaleOrder/Paginate - Test the search feature', function () {
    $datatableRequest = new SaleOrderDatatableRequest();
    $dataValidate = DatatableParams::createDatatableParams(
        [
            'code',
            'customer_name',
            'created_at',
            'payment_term',
            'total_quantity',
            'total_amount',
            'deliver_address',
            'order_status',
        ],
        [
            'globalSearch' => 'S00002',
        ]
    );
    $params = $datatableRequest->getDatatableParamsWithParams($dataValidate);
    $data = $this->saleOrderService->paginate($params);
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
    foreach ($categories['data'] as $saleOrder) {
        if (
            preg_match('/S00002/', strval($saleOrder['code'])) ||
            preg_match('/S00002/', strval($saleOrder['customer_name'])) ||
            preg_match('/S00002/', strval($saleOrder['created_at'])) ||
            preg_match('/S00002/', strval($saleOrder['deliver_address']))
        ) {
            $expect2 = true;
            break;
        }
    }
    //check data by name
    $this->assertTrue($expect2);
});

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

it('Invoice/sendMail sends an invoice email', function () {
    Mail::fake();
    $this->saleOrderService->sendInvoicePDF(2);
    $dataMail = $this->saleOrderService->getInvoicePDF(2);
    $dataMail['title'] = __('translation.invoice.invoice');
    Mail::assertSent(InvoiceMail::class, function ($mail) use ($dataMail) {
        $mail->build();
        expect($mail->subject)->toContain(__('translation.invoice.invoice'));
        return $mail->invoice = $dataMail;
    });
});

it('Invoice email job to the queue', function () {
    Queue::fake();
    $dataMail = $this->saleOrderService->getInvoicePDF(2);
    $dataMail['title'] = __('translation.invoice.invoice');
    SendInvoiceJob::dispatch($dataMail);
    Queue::assertPushed(SendInvoiceJob::class, function ($job) use ($dataMail) {
        return $job->invoice === $dataMail;
    });
});

it('SaleOrder/validateROG - Check validate ROG throw exception', function () {
    //Sale order have no sale_order_locations data
    $saleOrderIdNotValidateAllStock = SaleOrderDetail::select('sale_order_details.sale_order_id')
        ->leftJoin('sale_order_locations as sol', 'sol.sale_order_detail_id', 'sale_order_details.id')
        ->groupBy('sale_order_details.id')
        ->having(DB::raw('count(sol.id)'), 0)->first()->sale_order_id;
    $this->expectException(Exception::class);
    $this->saleOrderService->validateROG($saleOrderIdNotValidateAllStock, [
        'note' => 'note'
    ]);
});

it('SaleOrder/validateROG - Check validate ROG reduce product quantity', function () {
    $dataBeforeUpdate = SaleOrderDetail::select([
        'pl.quantity as quantity',
        'sol.quantity as picked_quantity'
    ])->leftJoin('sale_order_locations as sol', 'sol.sale_order_detail_id', 'sale_order_details.id')
        ->join('product_locations as pl', 'pl.id', 'sol.product_location_id')
        ->where('sale_order_details.sale_order_id', 4)->orderBy('sol.id', 'asc')->get();

    $this->saleOrderService->validateROG(4, [
        'note' => 'note'
    ]);

    $dataAfterUpdate = SaleOrderDetail::select([
        'pl.quantity as quantity',
    ])->leftJoin('sale_order_locations as sol', 'sol.sale_order_detail_id', 'sale_order_details.id')
        ->join('product_locations as pl', 'pl.id', 'sol.product_location_id')
        ->where('sale_order_details.sale_order_id', 4)->orderBy('sol.id', 'asc')->get();

    foreach ($dataBeforeUpdate as $key => $value) {
        $this->assertEquals($dataAfterUpdate[$key]['quantity'], $value['quantity'] - $value['picked_quantity']);
    }
});
