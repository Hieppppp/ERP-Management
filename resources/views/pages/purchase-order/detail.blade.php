@extends('layouts/main')


@section('styles')
    <style>
        #product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 40vh !important;
            overflow: auto !important;
        }

        #product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
            border-collapse: separate !important;
        }

        .fixed-header-table {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 4;
        }

        .fixed-footer-table {
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
            /* background-color: var(--primary-bg-color) !important; */
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

        .warning-icon {
            top: -13px;
            position: absolute;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.purchaseOrder.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ url('purchase-order') }}">{{ __('translation.purchaseOrder.purchaseOrder') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.detail') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">
                {{ __('translation.purchaseOrder.purchaseOrder') . ': ' . $purchaseOrder->code }}
            </h3>
            <div class="process-bar">
                <div class="progress px-1">
                    <div class="progress-bar checked" role="progressbar"
                        style="width: {{ $purchaseOrder->status == PurchaseOrderStatusEnum::DRAFT
                            ? '0'
                            : ($purchaseOrder->status == PurchaseOrderStatusEnum::PENDING
                                ? '33'
                                : ($purchaseOrder->status == PurchaseOrderStatusEnum::PENDING_SHELVE
                                    ? '66'
                                    : '99')) }}%;"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="step-container d-flex justify-content-between">
                    <div class="status-number">
                        <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i></div>
                        <div class="status-number">
                            <span>{{ __('translation.purchaseOrder.draft') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        @if (in_array($purchaseOrder->status, [
                                PurchaseOrderStatusEnum::PENDING,
                                PurchaseOrderStatusEnum::PENDING_SHELVE,
                                PurchaseOrderStatusEnum::DONE,
                                PurchaseOrderStatusEnum::CANCEL,
                            ]))
                            <div class="step-circle checked">
                                <i class="fa fa-check text-white" aria-hidden="true"></i>
                            </div>
                        @else
                            <div class="step-circle">
                                02
                            </div>
                        @endif
                        <div class="status-number">
                            <span>{{ __('translation.purchaseOrder.pending') }}</span>
                        </div>
                    </div>
                    @if ($purchaseOrder->status == PurchaseOrderStatusEnum::CANCEL)
                        <div class="status-number">
                            <div class="step-circle unchecked">
                                <i class="fa fa-times text-white" aria-hidden="true"></i>
                            </div>
                            <div class="status-number">
                                <span>{{ __('translation.purchaseOrder.cancel') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="status-number">
                            @if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::PENDING_SHELVE, PurchaseOrderStatusEnum::DONE]))
                                <div class="step-circle checked">
                                    <i class="fa fa-check text-white" aria-hidden="true"></i>
                                </div>
                            @else
                                <div class="step-circle">
                                    03
                                </div>
                            @endif
                            <div class="status-number">
                                <span>{{ __('translation.purchaseOrder.pendingShelve') }}</span>
                            </div>
                        </div>
                        <div class="status-number">
                            @if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::DONE]))
                                <div class="step-circle checked">
                                    <i class="fa fa-check text-white" aria-hidden="true"></i>
                                </div>
                            @else
                                <div class="step-circle">
                                    04
                                </div>
                            @endif
                            <div class="status-number">
                                <span>{{ __('translation.purchaseOrder.done') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="purchase_order_edit" class="jquery-validate-form border-bottom mb-3 pb-3"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_name">{{ __('translation.purchaseOrder.supplierName') }}</label>
                            <input type="text" class="form-control" name="supplier_name" id="supplier_name" required
                                value="{{ $purchaseOrder['supplier']['name'] }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="warehouse_name">{{ __('translation.purchaseOrder.shipTo') }}</label>
                            <input type="text" class="form-control" name="warehouse_name" id="warehouse_name" required
                                value="{{ implode(', ', [
                                    $purchaseOrder['warehouse']['detail_address'],
                                    $purchaseOrder['warehouse']['city'],
                                    $purchaseOrder['warehouse']['province'],
                                    $purchaseOrder['warehouse']['country'],
                                ]) }}"
                                disabled>
                        </div>
                        @if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::PENDING_SHELVE, PurchaseOrderStatusEnum::DONE]))
                            <div class="form-group d-flex flex-column">
                                <label>{{ __('translation.purchaseOrder.reference') }}</label>
                                <span><a
                                        href="{{ url('batch/' . $purchaseOrder->id) }}">{{ $purchaseOrder->batch_code }}</a></span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="scheduled_date">{{ __('translation.purchaseOrder.scheduleDate') }}</label>
                            <div class="input-group">
                                <div class="input-group-text bg-primary-transparent text-primary">
                                    <i class="fe fe-calendar text-20"></i>
                                </div>
                                <input class="form-control fc-datepicker" data-date-format="yyyy-mm-dd"
                                    name="scheduled_date" id="scheduled_date" placeholder="YYYY-MM-DD" type="text"
                                    value="{{ date('Y-m-d', strtotime($purchaseOrder?->scheduled_date)) }}" disabled>
                            </div>
                        </div>
                        @if ($purchaseOrder->status != PurchaseOrderStatusEnum::DRAFT)
                            <div class="col-xl-6 col-lg-12 form-group d-flex flex-column">
                                <label for="warehouse_name">{{ __('translation.purchaseOrder.receipt') }} <i
                                        class="fa fa-truck" aria-hidden="true"></i></label>
                                <span><a
                                        href="{{ url('purchase-order/' . $purchaseOrder->id . '/receive') }}">{{ $purchaseOrder->receipt_code }}</a></span>

                                @if ($purchaseOrder->returnOrders)
                                    @foreach ($purchaseOrder->returnOrders as $returnOrders)
                                        <span><a
                                                href="{{ url('return-order/' . $returnOrders->id . '/detail') }}">{{ $returnOrders->code }}</a></span>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tab-menu-heading border-bottom-0">
                    <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                        <nav id="navId" class="nav d-sm-flex d-block">
                            <p class="card-title">
                                {{ __('translation.purchaseOrder.listOrderProduct') }}
                            </p>
                        </nav>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabParentProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="product_datatable">
                                <thead class="fixed-header-table">
                                    <tr>
                                        <th class="text-filter">#</th>
                                        <th class="no-sort">{{ __('translation.product.image') }}</th>
                                        <th class="text-filter">{{ __('translation.product.name') }}</th>
                                        <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                        <th class="text-filter">{{ __('translation.product.category') }}</th>
                                        <th class="text-filter">{{ __('translation.product.quantity') }}</th>
                                        @if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::PENDING_SHELVE, PurchaseOrderStatusEnum::DONE]))
                                            <th class="text-filter">{{ __('translation.purchaseOrder.received') }}</th>
                                        @endif
                                        @if ($purchaseOrder->status == PurchaseOrderStatusEnum::DONE)
                                            <th class="text-filter">{{ __('translation.purchaseOrder.returned') }}
                                            </th>
                                        @endif
                                        <th class="text-filter">{{ __('translation.purchaseOrder.unitCost') }} (CAD)</th>
                                        <th class="text-filter">{{ __('translation.purchaseOrder.totalCost') }} (CAD)</th>
                                        @if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::PENDING_SHELVE, PurchaseOrderStatusEnum::DONE]))
                                            <th class="no-sort"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tfoot class="fixed-footer-table">
                                    <tr>
                                        @php
                                            $colSpan = 7;
                                            if ($purchaseOrder->status == PurchaseOrderStatusEnum::DONE) {
                                                $colSpan = 9;
                                            }
                                            if ($purchaseOrder->status == PurchaseOrderStatusEnum::PENDING_SHELVE) {
                                                $colSpan = 8;
                                            }
                                        @endphp
                                        <th colspan="{{ $colSpan }}" class="fw-bold">
                                            {{ __('translation.purchaseOrder.total') }}:
                                        </th>
                                        <th id='totalAmount' class="fw-bold">{{ number_format($totalAmount, 2) }}$</th>
                                        @if (in_array($purchaseOrder->status, [PurchaseOrderStatusEnum::PENDING_SHELVE, PurchaseOrderStatusEnum::DONE]))
                                            <th></th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($purchaseOrder->status == PurchaseOrderStatusEnum::DRAFT)
                    <button class="btn btn-primary" type="button"
                        title="{{ __('message.sendAndCreatePurchaseOrder') }}"
                        onclick="confirmSendPurchaseOrder()">{{ __('translation.purchaseOrder.sendOrder') }}</button>
                @endif
                @if ($purchaseOrder->status == PurchaseOrderStatusEnum::PENDING)
                    <div class="form-group">
                        <button class="btn btn-danger me-4" type="button"
                            onclick="cancel()">{{ __('translation.button.cancel') }}</button>
                        <a class="btn btn-primary"
                            href="{{ url('purchase-order/' . $purchaseOrder->id . '/receive') }}">{{ __('translation.purchaseOrder.receiveProduct') }}</a>
                    </div>
                @endif
                @if ($purchaseOrder->status == PurchaseOrderStatusEnum::PENDING_SHELVE)
                    <button class="btn btn-primary" type="button"
                        onclick="confirmFinishPurchaseOrder()">{{ __('translation.purchaseOrder.done') }}</button>
                @endif
                @if ($purchaseOrder->status == PurchaseOrderStatusEnum::DONE)
                    <a class="btn btn-primary"
                        href="{{ url('purchase-order/' . $purchaseOrder->id . '/return') }}">{{ __('translation.purchaseOrder.return') }}</a>
                @endif
            </form>
            @include('pages.purchase-order.log', ['logs' => $activityLogs])
        </div>

    </div>

    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        let purchaseOrder = @json($purchaseOrder);
        const products = @json($products);
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/purchase-order/purchase-order.detail.js') }}"></script>
@endSection()
