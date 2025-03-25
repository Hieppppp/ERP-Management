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

        .warning-icon {
            top: -13px;
            position: absolute;
        }

        @media only screen and (max-width: 696px) {
            .step-container {
                align-items: flex-start !important;
            }

            .progress {
                top: -18px;
            }
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
                <li class="breadcrumb-item"><a
                        href="{{ url('sale-order/' . $saleOrder['id']) }}">{{ __('translation.saleOrder.saleOrder') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $saleOrder['receipt_code'] }}
                </li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">{{ __('translation.saleOrder.receiptOfGood') }}</h3>
            <div class="process-bar">
                <div class="progress px-1">
                    @php
                        $progressRate = 50;
                        if ($saleOrder['receipt_status'] == SaleOrderReceiptStatusEnum::DONE) {
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
                        <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i></div>
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.ready') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        @if ($saleOrder['receipt_status'] == SaleOrderReceiptStatusEnum::DONE)
                            <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i>
                            </div>
                        @else
                            <div class="step-circle">03</div>
                        @endif
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.done') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h3 class="card-title">
                        {{ $saleOrder->receipt_code }}
                    </h3>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="form-group">
                        <label for="supplier_name">{{ __('translation.saleOrder.deliverAddress') }}</label>
                        <input type="text" class="form-control" value="{{ $saleOrder['deliver_address'] }}"
                            name="deliver_address" id="deliver_address" disabled>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="form-group">
                        <label for="warehouse_name">{{ __('translation.saleOrder.shipFrom') }}</label>
                        <input type="text" class="form-control" name="warehouse_name" id="warehouse_name"
                            value="{{ $saleOrder['warehouse']['address'] }}" disabled required>
                        <input type="text" class="form-control" name="warehouse_id"
                            value="{{ $saleOrder['warehouse_id'] }}" id="warehouse_id" value="" disabled hidden>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 form-group">
                    <div class="form-group d-flex flex-column">
                        <label>{{ __('translation.saleOrder.reference') }}</label>
                        <span><a href="{{ url('sale-order/' . $saleOrder['id']) }}">{{ $saleOrder->code }}</a></span>
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
                                    <th>{{ __('translation.saleOrder.orderQuantity') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabNote">
                        <textarea class="form-control" name="note" id="note" cols="30" rows="5"
                            placeholder="{{ __('translation.purchaseOrder.note') }} ...">{{ $saleOrder['note'] }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                @if ($saleOrder['receipt_status'] == SaleOrderReceiptStatusEnum::READY)
                    <button class="btn btn-primary" type="button"
                        onclick="confirmValidateReceiptStatus()">{{ __('translation.button.validate') }}</button>
                @endif
            </div>
        </div>
    </div>

    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const saleOrderId = "{{ $saleOrder['id'] }}";
        const inTransitStatus = '{{ SaleOrderStatusEnum::IN_TRANSIT }}';
        const warehouseId = "{{ $saleOrder['warehouse_id'] }}";
        let saleOrderDetails = @json($saleOrderDetails);
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/sale-order/sale-order.receipt.js') }}"></script>
@endSection()
