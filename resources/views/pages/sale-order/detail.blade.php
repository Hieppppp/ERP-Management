@extends('layouts/main')


@section('styles')
    <style>
        #sale_product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 60vh !important;
            overflow: auto !important;
        }

        #sale_product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
            border-collapse: separate !important;
        }

        .fiexd-header {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 4;
        }

        .fiexd-footer {
            position: sticky;
            bottom: 0;
            background-color: white;
            z-index: 4;
        }

        .step-container {
            position: relative;
            text-align: center;
            transform: translateY(-43%);
        }

        .checked {
            background-color: var(--primary-bg-color) !important;
        }

        .unchecked {
            background-color: red !important;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #ebecf5;
            line-height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .process-bar {
            min-width: 40%;
            height: 30px;
            top: 14px;
            position: relative;
        }

        .status-number {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-width: 100px;
        }

        .progress {
            height: 2px;
            top: -10px;
            width: calc(100% - 100px);
            margin: auto;
        }

        @media only screen and (max-width: 696px) {
            .step-container {
                align-items: flex-start !important;
            }

            .progress {
                top: -18px;
            }
        }

        .ckbox input[type=checkbox][disabled]+span::after {
            background-color: rgba(138, 139, 141, 0.35) !important;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.saleOrder.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('sale-order') }}">{{ __('translation.saleOrder.saleOrder') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.detail') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">{{ __('translation.saleOrder.saleOrder') }}</h3>
            <div class="process-bar">
                <div class="progress px-1">
                    @php
                        $progressRate = 0;
                        if ($saleOrder['order_status'] == SaleOrderStatusEnum::CONFIRM) {
                            $progressRate = 33;
                        } elseif ($saleOrder['order_status'] == SaleOrderStatusEnum::IN_TRANSIT) {
                            $progressRate = 66;
                        } elseif (
                            in_array($saleOrder['order_status'], [
                                SaleOrderStatusEnum::CANCEL,
                                SaleOrderStatusEnum::DELIVERED,
                            ])
                        ) {
                            $progressRate = 100;
                        }
                    @endphp
                    <div class="progress-bar checked" role="progressbar" style="width: {{ $progressRate }}%;"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="step-container d-flex justify-content-between">
                    <div class="status-number">
                        <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i></div>
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.draft') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        @if ($saleOrder['order_status'] != SaleOrderStatusEnum::DRAFT)
                            <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i></div>
                        @else
                            <div class="step-circle">02</div>
                        @endif
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.confirmed') }}</span>
                        </div>
                    </div>
                    @if ($saleOrder['order_status'] != SaleOrderStatusEnum::CANCEL)
                        <div class="status-number">
                            @if (in_array($saleOrder['order_status'], [SaleOrderStatusEnum::IN_TRANSIT, SaleOrderStatusEnum::DELIVERED]))
                                <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i>
                                </div>
                            @else
                                <div class="step-circle">03</div>
                            @endif
                            <div class="status-number">
                                <span>{{ __('translation.saleOrder.inTransit') }}</span>
                            </div>
                        </div>
                        <div class="status-number">
                            @if ($saleOrder['order_status'] == SaleOrderStatusEnum::DELIVERED)
                                <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i>
                                </div>
                            @else
                                <div class="step-circle">04</div>
                            @endif
                            <div class="status-number">
                                <span>{{ __('translation.saleOrder.delivered') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="status-number">
                            <div class="step-circle unchecked"><i class="fa fa-times text-white" aria-hidden="true"></i>
                            </div>
                            <div class="status-number">
                                <span>{{ __('translation.saleOrder.cancel') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h3 class="card-title">
                        {{ $saleOrder->code }}
                    </h3>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="form-group">
                        <label for="supplier_name">{{ __('translation.saleOrder.customerName') }}</label>
                        <input type="text" value="{{ $saleOrder['customer']['name'] }}" class="form-control"
                            name="customer_name" id="customer_name" disabled>
                    </div>
                    <div class="form-group">
                        <p id="client-address" class="text-muted">{{ $saleOrder['customer']['address'] }}</p>
                        <p id="client-phone" class="text-muted">{{ __('translation.customer.phoneNumber') }} :
                            {{ $saleOrder['customer_phone'] }}</p>
                        <p id="client-email" class="text-muted">{{ __('translation.customer.email') }} :
                            {{ $saleOrder['customer_email'] }}</p>
                    </div>
                    <div class="form-group">
                        <label for="supplier_name">{{ __('translation.saleOrder.invoiceAddress') }}</label>
                        <input type="text" class="form-control" value="{{ $saleOrder['customer_email'] }}"
                            name="invoice_address" id="invoice_address" disabled>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="form-group">
                        <label for="warehouse_name">{{ __('translation.saleOrder.shipFrom') }}</label>
                        <input type="text" class="form-control" name="warehouse_name" id="warehouse_name"
                            value="{{ $saleOrder['warehouse']['address'] }}" disabled required>
                    </div>
                    <div class="form-group">
                        <label for="supplier_name">{{ __('translation.saleOrder.deliverAddress') }}</label>
                        <input type="text" class="form-control" value="{{ $saleOrder['deliver_address'] }}"
                            name="deliver_address" id="deliver_address" disabled>
                    </div>
                    @if ($saleOrder['order_status'] != SaleOrderStatusEnum::DRAFT)
                        <div class="form-group d-flex flex-column">
                            <label>{{ __('translation.saleOrder.receipt') }} <i class="fa fa-truck"></i></label>
                            <span><a
                                    href="{{ url('sale-order/' . $saleOrder['id'] . '/receipt') }}">{{ $saleOrder->receipt_code }}</a></span>
                        </div>
                        <div class="form-group d-flex flex-column">
                            <label>{{ __('translation.saleOrder.customerInvoice') }} </label>
                            <span><a
                                    href="{{ url('invoice/' . $saleOrder['id']) }}">{{ $saleOrder->invoice_code }}</a></span>
                        </div>
                    @endif
                </div>
                <div class="col-xl-4 col-lg-12 form-group">
                    <div class="form-group">
                        <label for="supplier_name">{{ __('translation.saleOrder.deliverMethod') }}</label>
                        <select class="form-control select2-show-search form-select" name="deliver_method"
                            id="deliver_method" disabled>
                            @php
                                $methodMap = [
                                    '1' => __('translation.saleOrder.standardShipping'),
                                    '2' => __('translation.saleOrder.inStorePickup'),
                                ];
                            @endphp
                            <option></option>
                            @foreach (DeliverMethodEnum::getValues() as $methodValue)
                                <option value="{{ $methodValue }}"
                                    {{ $methodValue == $saleOrder['delivery_method'] ? 'selected' : '' }}>
                                    {{ $methodMap[$methodValue] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="supplier_name">{{ __('translation.saleOrder.paymentTerm') }}</label>
                        <select class="form-control select2-show-search form-select" name="payment_term"
                            id="payment_term" disabled>
                            @php
                                $paymentTermOptions = [
                                    1 => 'immediate',
                                    2 => '15_days',
                                    3 => '20_days',
                                    4 => '30_days',
                                    5 => '45_days',
                                    6 => 'end_month',
                                ];
                            @endphp
                            <option></option>
                            @foreach (PaymentTermTypeEnum::getValues() as $value)
                                <option value="{{ $value }}"
                                    {{ $value == $saleOrder['payment_term'] ? 'selected' : '' }}>
                                    {{ __('translation.paymentTerm.' . $paymentTermOptions[$value]) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="tab-menu-heading border-bottom-0">
                <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                    <nav id="navId" class="nav d-sm-flex d-block">
                        <a class="nav-link border border-bottom-lg-0 br-sm-5 active" data-bs-toggle="tab"
                            href="#tabProductList" id="productListLink">
                            {{ __('translation.saleOrder.list') }}
                        </a>
                        <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab" href="#tabNote"
                            id="noteLink">
                            {{ __('translation.purchaseOrder.note') }}
                        </a>
                    </nav>
                </div>
            </div>
            <div class="panel-body tabs-menu-body mb-5">
                <div class="tab-content">
                    <div class="tab-pane active" id="tabProductList">
                        <table class="table table-bordered w-100 text-nowrap border-bottom" id="sale_product_datatable">
                            <thead class="fiexd-header">
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('translation.product.image') }}</th>
                                    <th>{{ __('translation.product.name') }}</th>
                                    <th>{{ __('translation.product.unit') }}</th>
                                    <th>{{ __('translation.product.category') }}</th>
                                    <th>{{ __('translation.inventory.quantity') }}</th>
                                    <th>{{ __('translation.saleOrder.orderQuantity') }}</th>
                                    <th>{{ __('translation.saleOrder.unitPrice') }} (CAD)</th>
                                    <th>{{ __('translation.saleOrder.discount') }}</th>
                                    <th>{{ __('translation.saleOrder.totalPrice') }} (CAD)</th>
                                </tr>
                            </thead>
                            <tfoot class="fiexd-footer">
                                <tr></tr>
                                <tr>
                                    <th colspan="9" class="fw-bold text-end">
                                        {{ __('translation.saleOrder.taxes') }}:
                                    </th>
                                    <th class="fw-bold text-end" id="taxList">

                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="9" class="fw-bold text-end">
                                        {{ __('translation.saleOrder.totalAmount') }}:
                                    </th>
                                    <th id='totalAmount' class="fw-bold text-end">0.00$</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabNote">
                        <textarea class="form-control" name="note" id="note" cols="30" rows="5"
                            placeholder="{{ __('translation.purchaseOrder.note') }} ...">{{ $saleOrder['note'] }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                @if ($saleOrder['order_status'] == SaleOrderStatusEnum::DRAFT)
                    <button class="btn btn-primary" type="button"
                        onclick="confirmOrderStatus()">{{ __('translation.button.confirm') }}</button>
                @endif
                @if ($saleOrder['order_status'] == SaleOrderStatusEnum::CONFIRM)
                    <button class="btn btn-danger" type="button"
                        onclick="confirmCancelOrder()">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary" type="button"
                        onclick="confirmTransitOrder()">{{ __('translation.saleOrder.delivery') }}</button>
                @endif
                @if ($saleOrder['order_status'] == SaleOrderStatusEnum::IN_TRANSIT)
                    <button class="btn btn-primary" type="button"
                        onclick="confirmDoneOrder()">{{ __('translation.saleOrder.done') }}</button>
                @endif
            </div>
        </div>
    </div>

    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const draftStatus = '{{ SaleOrderStatusEnum::DRAFT }}';
        const confirmStatus = '{{ SaleOrderStatusEnum::CONFIRM }}';
        const inTransitStatus = '{{ SaleOrderStatusEnum::IN_TRANSIT }}';
        const cancelStatus = '{{ SaleOrderStatusEnum::CANCEL }}';
        const deliverdStatus = '{{ SaleOrderStatusEnum::DELIVERED }}';
        const receiptDoneStatus = '{{ SaleOrderReceiptStatusEnum::DONE }}';
        const saleOrderId = "{{ $saleOrder['id'] }}";
        const saleOrderDetails = @json($saleOrderDetails);
        const saleOrder = @json($saleOrder);
        saleOrder.customer.address = saleOrder.customer_address;
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/sale-order/sale-order.detail.js') }}"></script>
@endSection()
