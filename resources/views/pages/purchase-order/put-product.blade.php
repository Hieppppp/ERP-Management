@extends('layouts/main')


@section('styles')
    <style>
        #shelve_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 40vh !important;
            overflow: auto !important;
        }

        #shelve_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
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
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.purchaseOrder.productAssignment') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ url('purchase-order/' . $purchaseOrder->id) }}">{{ $purchaseOrder->code }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.purchaseOrder.productAssignment') }}
                </li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">
                {{ $purchaseOrder['products'][0]['name'] }}
            </h3>
        </div>
        <div class="card-body">
            <form id="stock_product" class="jquery-validate-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="supplier_name">{{ __('translation.purchaseOrder.supplierName') }}</label>
                        <input type="text" class="form-control" name="supplier_name" id="supplier_name"
                            value="{{ $purchaseOrder['supplier']['name'] }}" disabled>
                    </div>
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="received_quantity">{{ __('translation.purchaseOrder.receivedQuantity') }}</label>
                        <input type="text" class="form-control" name="received_quantity" id="received_quantity"
                            value="{{ $purchaseOrder['products'][0]['pivot']['received_quantity'] }}" disabled>
                    </div>
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="received_quantity">{{ __('translation.product.supplierSku') }}</label>
                        <input type="text" class="form-control" name="received_quantity" id="sku" disabled>
                    </div>
                </div>
                <div class="tab-menu-heading border-bottom-0">
                    <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                        <nav id="navId" class="nav d-sm-flex d-block">
                            <p class="card-title">
                                {{ __('translation.shelve.list') }}
                            </p>
                        </nav>
                        <button class="btn btn-primary mh-10" type="button"
                            onclick="addShelve()">{{ __('translation.add') }}</button>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabParentProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="shelve_datatable">
                                <thead class="fixed-header-table">
                                    <tr>
                                        <th class="text-filter">#</th>
                                        <th class="no-sort">{{ __('translation.shelve.shelveName') }} </th>
                                        <th class="text-filter">{{ __('translation.shelve.location') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.quantity') }}</th>
                                        <th class="text-filter">{{ __('translation.action') }}</th>
                                    </tr>
                                </thead>
                                <tfoot class="fixed-footer-table">
                                    <tr>
                                        <th colspan="3">{{ __('translation.purchaseOrder.total') }}:</th>
                                        <th id='totalAmount'>0.00</th>
                                        <th> </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger me-4" type="button"
                        onclick="back()">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary" type="button"
                        onclick="save()">{{ __('translation.purchaseOrder.validate') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        let purchaseOrder = @json($purchaseOrder)
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/purchase-order/put-product.js') }}"></script>
@endSection()
