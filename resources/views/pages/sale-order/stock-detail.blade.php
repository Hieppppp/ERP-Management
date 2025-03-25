@extends('layouts/main')


@section('styles')
    <style>
        #stock_table_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 40vh !important;
            overflow: auto !important;
        }

        #stock_table_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
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
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.saleOrder.productAssignment') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ url('sale-order/' . $saleOrderDetail->saleOrder->id . '/receipt') }}">{{ __('translation.saleOrder.receipt') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.saleOrder.productAssignment') }}
                </li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">
                {{ $saleOrderDetail['product']['name'] }}
            </h3>
        </div>
        <div class="card-body">
            <form id="select_stock" class="jquery-validate-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="order_quantity">{{ __('translation.saleOrder.orderQuantity') }}</label>
                        <input type="text" class="form-control" name="order_quantity" id="order_quantity"
                            value="{{ $saleOrderDetail['quantity'] }}" disabled>
                    </div>
                </div>
                <div class="tab-menu-heading border-bottom-0">
                    <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                        <nav id="navId" class="nav d-sm-flex d-block">
                            <p class="card-title">
                                {{ __('translation.shelve.shelveList') }}
                            </p>
                        </nav>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabParentProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="stock_table">
                                <thead class="fixed-header-table">
                                    <tr>
                                        <th class="text-filter">{{ __('translation.batch.batch') }}</th>
                                        <th class="text-filter">{{ __('translation.batch.receivedDate') }}</th>
                                        <th class="no-sort">{{ __('translation.shelve.shelve') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.warehouse') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.location') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.stockQuantity') }}</th>
                                        <th class="text-filter">{{ __('translation.saleOrder.pickedQuantity') }}</th>
                                    </tr>
                                </thead>
                                <tfoot class="fiexd-footer">
                                    <tr></tr>
                                    <tr>
                                        <th colspan="6" class="fw-bold text-end">
                                            {{ __('translation.saleOrder.total') }}:
                                        </th>
                                        <th id='totalStock' class="fw-bold text-start">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const saleOrderDetail = @json($saleOrderDetail);
        const inventoryData = @json($productInventory);
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/sale-order/sale-order.stock-detail.js') }}"></script>
@endSection()
