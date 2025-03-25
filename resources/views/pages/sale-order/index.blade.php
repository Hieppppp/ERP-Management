@extends('layouts/main')


@section('styles')
    <style>
        .amount {
            padding: 7px;
            background-color: var(--primary-bg-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 8px;
            width: 120px;
            margin-bottom: 10px;
            margin-right: 10px;
        }

        .width-status {
            width: 100px;
            font-size: 10px;
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
            <h1 class="page-title">{{ __('translation.saleOrder.management') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.saleOrder.list') }}</h3>
                    <a class="btn btn-primary"
                        href="{{ url('sale-order/create') }}">{{ __('translation.saleOrder.create') }}</a>
                </div>
                <div class="card-body overflow-auto">
                    <div class="d-flex">
                        <div class="amount" style="background: #C4C4C4;">
                            <span class="text-center fw-bold text-white">
                                {{ $saleOrderCount['draft_count'] ?? 0 }}
                            </span>
                            <span class="text-center fw-bold text-white">{{ trans('translation.saleOrder.draft') }}</span>
                        </div>
                        <div class="amount" style="background: #82C035;">
                            <span class="text-center fw-bold text-white">
                                {{ $saleOrderCount['confirmed_count'] ?? 0 }}
                            </span>
                            <span class="text-center fw-bold text-white">{{ trans('translation.saleOrder.confirm') }}</span>
                        </div>
                        <div class="amount" style="background: #EA86B3;">
                            <span class="text-center fw-bold text-white">
                                {{ $saleOrderCount['in_transit_count'] ?? 0 }}
                            </span>
                            <span
                                class="text-center fw-bold text-white">{{ trans('translation.saleOrder.inTransit') }}</span>
                        </div>
                        <div class="amount" style="background: #2C7865;">
                            <span class="text-center fw-bold text-white">
                                {{ $saleOrderCount['delivered_count'] ?? 0 }}
                            </span>
                            <span
                                class="text-center fw-bold text-white">{{ trans('translation.saleOrder.delivered') }}</span>
                        </div>
                        <div class="amount" style="background: #B03C3C;">
                            <span class="text-center fw-bold text-white">
                                {{ $saleOrderCount['cancel_count'] ?? 0 }}
                            </span>
                            <span class="text-center fw-bold text-white">{{ trans('translation.saleOrder.cancel') }}</span>
                        </div>
                    </div>
                    <table class="table table-bordered w-100 text-nowrap border-bottom" id="sale-order-datatable">
                        <thead>
                            <tr>
                                <th class="text-filter">{{ __('translation.saleOrder.id') }}</th>
                                <th class="text-filter">{{ __('translation.saleOrder.customerName') }}</th>
                                <th class="text-filter">{{ __('translation.saleOrder.orderDate') }}</th>
                                <th class="no-sort">{{ __('translation.saleOrder.scheduledDate') }}</th>
                                <th>{{ __('translation.saleOrder.totalQuantity') }}</th>
                                <th>{{ __('translation.saleOrder.totalAmount') }}</th>
                                <th class="text-filter no-sort">{{ __('translation.saleOrder.shippingAddress') }}</th>
                                <th class="select-filter no-sort" id="saleOrderStatus">
                                    {{ __('translation.saleOrder.status') }}</th>
                                <th class="no-sort">{{ __('translation.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade delete-modal" id="deleteSaleOrder" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('translation.modal.confirmDelete') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ __('message.confirmDelete') }}</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="deleteBtn">{{ __('translation.yes') }}</button>
                    <button class="btn btn-info" data-bs-dismiss="modal">{{ __('translation.no') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->
@endSection()

@section('scripts')
    <script src="{{ asset('assets/js/page/sale-order/sale-order.datatable.js') }}"></script>
@endSection()
