@extends('layouts/main')


@section('styles')
    <style>
        #log-datatable ul {
            list-style-type: disc;
            padding-left: 2rem;
            font-size: small;
        }

        #log-datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 100vh !important;
            overflow: auto !important;
        }

        #log-datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
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

        .width-status {
            width: 100px;
            font-size: 10px;
            cursor: unset !important;
        }

        .draft {
            border: 1px solid #C4C4C4 !important;
            color: #C4C4C4 !important;
        }

        .pending {
            border: 1px solid #FF9800 !important;
            color: #FF9800 !important;
        }

        .pending_shelve {
            border: 1px solid #2C7865 !important;
            color: #2C7865 !important;
        }

        .done {
            border: 1px solid #2C7865 !important;
            color: #2C7865 !important;
        }

        .cancel {
            border: 1px solid #B03C3C !important;
            color: #B03C3C !important;
        }

        .ready {
            border: 1px solid #FF9800 !important;
            color: #FF9800 !important;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.receipt.management') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.receipt.list') }}</h3>
                </div>
                <div class="card-body overflow-auto">
                    <div class="tab-menu-heading border-bottom-0">
                        <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                            <!-- Tabs -->
                            <nav id="navId" class="nav d-sm-flex d-block">
                                <a class="nav-link border border-bottom-0 br-sm-5 active" data-bs-toggle="tab"
                                    href="#tabReceiptIn" id="tabReceiptInLink">
                                    {{ __('translation.receipt.receiptIn') }}
                                </a>
                                <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab"
                                    href="#tabReceiptOut" id="tabReceiptOutLink">
                                    {{ __('translation.receipt.receiptOut') }}
                                </a>
                            </nav>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body mb-5">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabReceiptIn">
                                <table class="table table-bordered text-nowrap border-bottom w-100"
                                    id="receipt-in-datatable">
                                    <thead class="fiexd-header">
                                        <tr>
                                            <th class="text-filter">{{ __('translation.receipt.id') }}</th>
                                            <th>{{ __('translation.receipt.createdDate') }}</th>
                                            <th class="text-filter">{{ __('translation.receipt.supplierName') }}</th>
                                            <th>{{ __('translation.receipt.quantityDemand') }}</th>
                                            <th>{{ __('translation.receipt.quantityReceived') }}</th>
                                            <th class="text-filter">{{ __('translation.receipt.shippingAddress') }}</th>
                                            <th class="no-sort select-filter" id="status-in">
                                                {{ __('translation.receipt.status') }}
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane" id="tabReceiptOut">
                                <table class="table table-bordered text-nowrap border-bottom w-100"
                                    id="receipt-out-datatable">
                                    <thead class="fiexd-header">
                                        <tr>
                                            <th class="text-filter">{{ __('translation.receipt.id') }}</th>
                                            <th>{{ __('translation.receipt.createdDate') }}</th>
                                            <th class="text-filter">{{ __('translation.receipt.supplierName') }}</th>
                                            <th>{{ __('translation.receipt.quantityDemand') }}</th>
                                            <th>{{ __('translation.receipt.quantityReturned') }}</th>
                                            <th class="text-filter">{{ __('translation.receipt.shippingAddress') }}</th>
                                            <th class="select-filter" id="status-out">
                                                {{ __('translation.receipt.status') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->
@endSection()

@section('scripts')
    <script src="{{ asset('assets/js/page/purchase-order/receipt.js') }}"></script>
@endSection()
