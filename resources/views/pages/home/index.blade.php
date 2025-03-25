@extends('layouts/main')


@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/date-range-picker/daterangepicker.css') }}" />
    <style>
        .table-overflow {
            min-height: 280px;
            overflow-x: auto;
        }

        .dataTables_wrapper .row:nth-of-type(2) {
            overflow: initial !important;
        }

        .step-circle {
            padding-top: 1px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #D9EDBF;
            border: 2px solid #D9EDBF;
            line-height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-bg-color);
        }

        .width-status {
            width: 115px;
            font-size: 10px;
            cursor: unset !important;
        }

        .pending {
            border: 1px solid #FF9800 !important;
            color: #FF9800 !important;
        }

        .awaiting_payment {
            border: 1px solid #EA86B3 !important;
            color: #EA86B3 !important;
        }

        .pending_shipment {
            border: 1px solid #82C035 !important;
            color: #82C035 !important;
        }

        #pending-order-datatable.dataTable {
            border: none !important;
        }

        #pending-order-datatable.dataTable thead th,
        #pending-order-datatable.dataTable tbody td {
            border: none !important;
        }

        #pending-order-datatable.dataTable thead {
            border-bottom: none !important;
            background-color: #FAFDF7;
        }

        .card-header {
            height: 80px;
        }

        .card-body a {
            color: black !important;
        }

        .card-body a:hover {
            color: var(--primary-bg-color) !important;
        }

        .status-1 {
            border: 1px solid #C4C4C4 !important;
            color: #C4C4C4 !important;
        }

        .status-2 {
            border: 1px solid #FF9800 !important;
            color: #FF9800 !important;
        }

        .status-3 {
            border: 1px solid #82C035 !important;
            color: #82C035 !important;
        }

        .status-4 {
            border: 1px solid #2C7865 !important;
            color: #2C7865 !important;
        }

        .status-5 {
            border: 1px solid #B03C3C !important;
            color: #B03C3C !important;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.menu.dashboard') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="mb-2 fw-semibold" id="total_inventory_value">0.00$</h4>
                            <p class="fs-13 mb-0">{{ __('translation.dashboard.totalInventoryValue') }} : <span
                                    id="total_inventory_value_child">0.00$</span></p>
                        </div>
                        <div class="col col-auto top-icn dash">
                            <div class="counter-icon bg-primary ms-auto box-shadow-warning">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="mb-2 fw-semibold" id="average_inventory_value">0.00$</h4>
                            <p class="fs-13 mb-0">{{ __('translation.dashboard.averageInventoryCost') }}</p>
                        </div>
                        <div class="col col-auto top-icn dash">
                            <div class="counter-icon bg-secondary ms-auto box-shadow-secondary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="mb-2 fw-semibold" id="total_purchase_order">0.00</h4>
                            <p class="fs-13 mb-0">{{ __('translation.dashboard.purchaseOrder') }}</p>
                        </div>
                        <div class="col col-auto top-icn dash">
                            <div class="counter-icon bg-info ms-auto box-shadow-info">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="mb-2 fw-semibold" id="total_sales_order">0</h4>
                            <p class="fs-13 mb-0">{{ __('translation.dashboard.saleOrder') }}</p>
                        </div>
                        <div class="col col-auto top-icn dash">
                            <div class="counter-icon bg-warning ms-auto box-shadow-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card product-sales-main">
                <div class="card-header border-bottom dlex justify-content-between">
                    <h3 class="card-title mb-0">{{ __('translation.dashboard.currentStockLevel') }}</h3>
                    @if (PermissionRole::checkPermission([Permission::PRODUCT]))
                        <a href="/product?orderBy=quantity&orderType=desc"
                            target="_blank">{{ __('translation.dashboard.viewDetail') }}</a>
                    @endif
                </div>
                <div class="card-body table-overflow" id="current-stock">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card product-sales-main">
                <div class="card-header border-bottom dlex justify-content-between">
                    <h3 class="card-title mb-0">{{ __('translation.dashboard.lowStockAlert') }}</h3>
                    @if (PermissionRole::checkPermission([Permission::PRODUCT]))
                        <a href="/product?orderBy=quantity&orderType=asc&quantityFilter=below"
                            target="_blank">{{ __('translation.dashboard.viewDetail') }}</a>
                    @endif
                </div>
                <div class="card-body table-overflow" id="low-stock">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card product-sales-main">
                <div class="card-header border-bottom dlex justify-content-between">
                    <h3 class="card-title mb-0">{{ __('translation.dashboard.recentOrder') }}</h3>
                    @if (PermissionRole::checkPermission([Permission::PRODUCT]))
                        <a href="/sale-order" target="_blank">{{ __('translation.dashboard.viewDetail') }}</a>
                    @endif
                </div>
                <div class="card-body table-overflow" id="recent-orders">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card product-sales-main">
                <div class="card-header border-bottom dlex justify-content-between">
                    <h3 class="card-title mb-0">{{ __('translation.dashboard.sellingItem') }}</h3>
                    <select class="form-control select2-show-search form-select" name="filter-day" id="filter-day"
                        style="max-width:150px;">
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month" selected>This Month</option>
                    </select>
                </div>
                <div class="card-body table-overflow" id="top-selling">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card product-sales-main">
                <div class="card-header border-bottom dlex justify-content-between">
                    <h3 class="card-title mb-0">{{ __('translation.dashboard.pendingOrder') }}</h3>

                </div>
                <div class="card-body table-overflow">
                    <table class="table table-bordered w-100 text-nowrap border-bottom" id="pending-order-datatable">
                        <thead>
                            <tr>
                                <th class="text-filter"></th>
                                <th class="text-filter">{{ __('translation.order.name') }}</th>
                                <th class="text-filter">{{ __('translation.order.orderDate') }}</th>
                                <th class="text-filter">{{ __('translation.order.customerName') }}</th>
                                <th>{{ __('translation.purchaseOrder.totalAmount') }}
                                    (CAD)</th>
                                <th class="text-filter">{{ __('translation.order.shippingAddress') }}</th>
                                <th class="select-filter">{{ __('translation.order.shippingMethod') }}</th>
                                <th class="select-filter">{{ __('translation.order.status') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endSection()

@section('scripts')
    <script src="{{ asset('assets/js/page/dashboard/index.js') }}"></script>
@endSection()
