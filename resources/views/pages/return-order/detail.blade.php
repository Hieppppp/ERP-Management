@extends('layouts/main')

@section('styles')
    <style>
        #return-product-select_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 50vh !important;
            overflow: auto !important;
        }

        #return-product-select_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
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
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                {{ request()->get('page') == 'receipt' ? __('translation.receipt.management') : __('translation.purchaseOrder.management') }}
            </h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    @if (request()->get('page') == 'receipt')
                        <a href="{{ url('receipt') }}">{{ __('translation.receipt.receipt') }}</a>
                    @else
                        <a href="{{ url('purchase-order/' . $purchaseOrder->id) }}">{{ $purchaseOrder->code }}</a>
                    @endif
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if (request()->get('page') == 'receipt')
                        {{ $returnOrder->code }}
                    @else
                        {{ __('translation.returnOrder.returnTransfer') }}
                    @endif
                </li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">
                {{ $returnOrder->code }}
            </h3>
            <div class="process-bar">
                <div class="progress px-1">
                    <div class="progress-bar checked" role="progressbar" style="width: 100%;" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="step-container d-flex justify-content-between">
                    <div class="status-number">
                        <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i></div>
                        <div class="status-number">
                            <span>{{ __('translation.returnOrder.draft') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i></div>
                        <div class="status-number">
                            <span>{{ __('translation.returnOrder.ready') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        @if ($returnOrder->status == ReturnOrderStatusEnum::DONE)
                            <div class="step-circle checked"><i class="fa fa-check text-white" aria-hidden="true"></i></div>
                            <div class="status-number">
                                <span>{{ __('translation.returnOrder.done') }}</span>
                            </div>
                        @else
                            <div class="step-circle unchecked"><i class="fa fa-times text-white" aria-hidden="true"></i>
                            </div>
                            <div class="status-number">
                                <span>{{ __('translation.returnOrder.cancel') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-12 form-group">
                    <label for="supplier_name">{{ __('translation.purchaseOrder.supplierName') }}</label>
                    <input type="text" class="form-control" name="supplier_name" id="supplier_name"
                        value="{{ $purchaseOrder['supplier']['name'] }}" disabled>
                </div>
                <div class="col-xl-4 col-lg-12 form-group">
                    <label for="scheduled_date">{{ __('translation.purchaseOrder.scheduleDate') }}</label>
                    <div class="input-group">
                        <div class="input-group-text bg-primary-transparent text-primary">
                            <i class="fe fe-calendar text-20"></i>
                        </div>
                        <input class="form-control fc-datepicker" readonly data-date-format="yyyy-mm-dd"
                            name="scheduled_date" id="scheduled_date" placeholder="YYYY-MM-DD" type="text"
                            value="{{ date('Y-m-d', strtotime($purchaseOrder?->scheduled_date)) }}" disabled>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 form-group d-flex flex-column">
                    <label>{{ __('translation.purchaseOrder.reference') }}</label>
                    <span><a
                            href="{{ url('purchase-order/' . $purchaseOrder->id) }}">{{ $purchaseOrder->code }}</a></span>
                </div>
            </div>
            <div class="tab-menu-heading border-bottom-0">
                <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                    <nav id="navId" class="nav d-sm-flex d-block">
                        <a class="nav-link border border-bottom-lg-0 br-sm-5 active" data-bs-toggle="tab"
                            href="#tabReturnOderList" id="returnOderTabListLink">
                            {{ __('translation.returnOrder.list') }}
                        </a>
                        <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab" href="#tabNote"
                            id="tabNoteLink">
                            {{ __('translation.purchaseOrder.note') }}
                        </a>
                    </nav>
                </div>
            </div>
            <div class="panel-body tabs-menu-body mb-5">
                <div class="tab-content">
                    <div class="tab-pane active" id="tabReturnOderList">
                        <table class="table table-bordered w-100 text-nowrap border-bottom" id="return_product_datatable">
                            <thead class="fixed-header-table">
                                <tr>
                                    <th class="text-filter">#</th>
                                    <th class="no-sort">{{ __('translation.product.image') }}</th>
                                    <th class="text-filter">{{ __('translation.product.name') }}</th>
                                    <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                    <th class="text-filter">{{ __('translation.product.category') }}</th>
                                    <th class="text-filter">{{ __('translation.shelve.shelve') }}</th>
                                    <th class="no-sort">{{ __('translation.returnOrder.demandQuantity') }}</th>
                                    <th class="no-sort">{{ __('translation.returnOrder.quantity') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabNote">
                        <textarea class="form-control" cols="30" rows="5" disabled>{{ $returnOrder->note }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const returnOrderDetails = @json($returnOrderDetails);
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/return-order/return-order.detail.js?v=1') }}"></script>
@endSection()
