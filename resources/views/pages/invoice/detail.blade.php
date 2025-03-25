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

        /* HTML: <div class="ribbon">Your text content</div> */
        .ribbon {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            z-index: 1 !important;
            overflow: hidden !important;
            width: 178px !important;
            height: 45px !important;
            text-align: right !important;
        }

        .ribbon {
            --f: .5em;
            /* control the folded part */

            position: absolute;
            top: 4px;
            right: 4px;
            line-height: 1.8;
            padding-inline: 1lh;
            padding-bottom: var(--f);
            border-image: conic-gradient(#fff 0 0) 51%/var(--f);
            clip-path: polygon(100% calc(100% - var(--f)), 100% 100%, calc(100% - var(--f)) calc(100% - var(--f)), var(--f) calc(100% - var(--f)), 0 100%, 0 calc(100% - var(--f)), 999px calc(100% - var(--f) - 999px), calc(100% - 999px) calc(100% - var(--f) - 999px));
            transform: translate(calc((1 - cos(45deg))*100%), -100%) rotate(45deg);
            transform-origin: 0% 100%;
            background-color: #EA86B3;
            /* the main color  */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-header {
            border-bottom: 0px !important;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.invoice.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('invoice') }}">{{ __('translation.invoice.invoice') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.detail') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">{{ $saleOrder->invoice_code }}</h3>
            @if (count($registerPayments) > 0)
                <div class="ribbon">
                    {{ $totalAmountUnpaid <= 0 ? __('translation.invoice.paid') : __('translation.invoice.partially_paid') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
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
                        <label for="supplier_name">{{ __('translation.saleOrder.deliverAddress') }}</label>
                        <input type="text" class="form-control" value="{{ $saleOrder['deliver_address'] }}"
                            name="deliver_address" id="deliver_address" disabled>
                    </div>
                    <div class="form-group">
                        <label for="supplier_name">{{ __('translation.saleOrder.paymentTerm') }}</label>
                        <select class="form-control select2-show-search form-select" name="payment_term" id="payment_term"
                            disabled>
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
                    <div class="form-group d-flex flex-column">
                        <label>{{ __('translation.saleOrder.reference') }}</label>
                        <span><a href="{{ url('sale-order/' . $saleOrder['id']) }}">{{ $saleOrder->code }}</a></span>
                    </div>
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
                                        <div>
                                            {{ __('translation.saleOrder.totalAmount') }}:
                                        </div>
                                        @if (count($registerPayments) > 0)
                                            @foreach ($registerPayments as $registerPayment)
                                                <div>
                                                    {{ __('translation.invoice.paidOn') . ' ' . $registerPayment['date'] }}:
                                                </div>
                                            @endforeach
                                            <div>
                                                {{ __('translation.invoice.amountDue') }}
                                            </div>
                                        @endif
                                    </th>
                                    <th class="fw-bold text-end">
                                        <div id='totalAmount'>0.00$</div>
                                        @if (count($registerPayments) > 0)
                                            @foreach ($registerPayments as $registerPayment)
                                                <div>{{ number_format($registerPayment['paid_amount'], 2) }}$</div>
                                            @endforeach
                                            <div>{{ number_format($totalAmountUnpaid, 2) }}$</div>
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabNote">
                        <textarea class="form-control" name="note" id="note" cols="30" rows="5" disabled>{{ $saleOrder['note'] }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="button"
                    onclick="openPdf()">{{ __('translation.button.send') }}</button>
                @if ($totalAmountUnpaid > 0)
                    <button class="btn btn-primary" type="button"
                        onclick="registerPayment()">{{ __('translation.invoice.registerPayment') }}</button>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade delete-modal" id="confirmModal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('translation.modal.confirm') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('message.confirmAction') }}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-dismiss="modal">{{ __('translation.no') }}</button>
                    <button class="btn btn-primary"
                        onclick="registerPaymentSubmit()">{{ __('translation.yes') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="invoiceModal">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content country-select-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModal-header"></h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <iframe id="invoice_content" style="width:100%; height:70vh; border:none;"></iframe>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button"
                        onclick="printPDF()">{{ __('translation.button.print') }}</button>
                    <button class="btn btn-primary" type="button"
                        onclick="sendInvoice()">{{ __('translation.button.send') }}</button>
                </div>

            </div>
        </div>
    </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const draftStatus = '{{ SaleOrderStatusEnum::DRAFT }}';
        const confirmStatus = '{{ SaleOrderStatusEnum::CONFIRM }}';
        const saleOrderId = "{{ $saleOrder['id'] }}";
        let saleOrderDetails = @json($saleOrderDetails);
        let saleOrder = @json($saleOrder);
        saleOrder.customer.address = saleOrder.customer_address;
        const invoiceCreationDate = "{{ date('Y-m-d', strtotime($saleOrder?->created_at)) }}";
        const currentDate = "{{ date('Y-m-d') }}";
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/sale-order/sale-order.detail.js') }}"></script>
    <script src="{{ asset('assets/js/page/invoice/invoice.detail.js') }}"></script>
@endSection()
