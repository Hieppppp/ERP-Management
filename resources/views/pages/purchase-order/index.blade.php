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

        .draft {
            border: 1px solid #C4C4C4 !important;
            color: #C4C4C4 !important;
        }

        .pending {
            border: 1px solid #FF9800 !important;
            color: #FF9800 !important;
        }

        .pending_shelve {
            border: 1px solid #82C035 !important;
            color: #82C035 !important;
        }

        .done {
            border: 1px solid #2C7865 !important;
            color: #2C7865 !important;
        }

        .cancel {
            border: 1px solid #B03C3C !important;
            color: #B03C3C !important;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.purchaseOrder.management') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.purchaseOrder.list') }}</h3>
                    <a class="btn btn-primary"
                        href="{{ url('purchase-order/create') }}">{{ __('translation.purchaseOrder.create') }}</a>
                </div>
                <div class="card-body overflow-auto">
                    <div class="d-flex">
                        <div class="amount" style="background: #C4C4C4;">
                            <span class="text-center fw-bold text-white">
                                {{ !empty($purchaseOrderCount['draft']['total']) ? $purchaseOrderCount['draft']['total'] : 0 }}
                            </span>
                            <span
                                class="text-center fw-bold text-white">{{ trans('translation.purchaseOrder.draft') }}</span>
                        </div>
                        <div class="amount" style="background: #FF9800;">
                            <span class="text-center fw-bold text-white">
                                {{ !empty($purchaseOrderCount['pending']['total']) ? $purchaseOrderCount['pending']['total'] : 0 }}
                            </span>
                            <span
                                class="text-center fw-bold text-white">{{ trans('translation.purchaseOrder.pending') }}</span>
                        </div>
                        <div class="amount" style="background: #82C035;">
                            <span class="text-center fw-bold text-white">
                                {{ !empty($purchaseOrderCount['pending_shelve']['total']) ? $purchaseOrderCount['pending_shelve']['total'] : 0 }}
                            </span>
                            <span
                                class="text-center fw-bold text-white">{{ trans('translation.purchaseOrder.pendingShelve') }}</span>
                        </div>
                    </div>
                    <table class="table table-bordered w-100 text-nowrap border-bottom" id="purchase-order-datatable">
                        <thead>
                            <tr>
                                <th class="text-filter">{{ __('translation.purchaseOrder.code') }}</th>
                                <th class="text-filter">{{ __('translation.purchaseOrder.createdDate') }}</th>
                                <th class="text-filter">{{ __('translation.purchaseOrder.scheduleDate') }}</th>
                                <th class="text-filter">{{ __('translation.purchaseOrder.supplierName') }}</th>
                                <th>{{ __('translation.purchaseOrder.totalAmount') }}
                                    (CAD)</th>
                                <th class="text-filter">{{ __('translation.purchaseOrder.shipTo') }}</th>
                                <th class="select-filter" id="purchaseOrderStatus">
                                    {{ __('translation.purchaseOrder.status') }}</th>
                                <th class="no-sort">{{ __('translation.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade delete-modal" id="deletePurchaseOrder" tabindex="-1" role="dialog">
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
    <script src="{{ asset('assets/js/page/purchase-order/purchase-order.datatable.js') }}"></script>
@endSection()
