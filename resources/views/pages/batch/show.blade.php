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
            <h1 class="page-title">{{ __('translation.batch.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('batch') }}">{{ __('translation.batch.management') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('translation.batch.detail') }}
                </li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">
                {{ $batch['batch_code'] }}
            </h3>
        </div>
        <div class="card-body">
            <form id="purchase_order_receive" class="jquery-validate-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-6 col-lg-12 form-group">
                        <label for="supplier_name">{{ __('translation.batch.supplierName') }}</label>
                        <input type="text" class="form-control" name="supplier_name" id="supplier_name" required
                            value="{{ $batch['supplier_name'] }}" disabled>
                    </div>
                    <div class="col-xl-6 col-lg-12 form-group">
                        <label for="warehouse_name">{{ __('translation.batch.storageLocation') }}</label>
                        <input type="text" class="form-control" name="warehouse_name" id="warehouse_name" required
                            value="{{ $batch['storage_location'] }}" disabled>
                    </div>
                </div>
                <div class="tab-menu-heading border-bottom-0">
                    <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                        <nav id="navId" class="nav d-sm-flex d-block">
                            <a class="nav-link border border-bottom-0 br-sm-5 active" data-bs-toggle="tab"
                                href="#tabParentProduct" id="tabParentProductLink">
                                {{ __('translation.batch.listProduct') }}
                            </a>
                            <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab" href="#tabNote"
                                id="tabNoteLink">
                                {{ __('translation.purchaseOrder.purchaseOrder') }}
                            </a>
                        </nav>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabParentProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="product_datatable">
                                <thead class="fixed-header-table">
                                    <tr>
                                        <th class="text-filter">{{ __('translation.batch.id') }}</th>
                                        <th class="no-sort">{{ __('translation.product.image') }}</th>
                                        <th class="text-filter">{{ __('translation.product.name') }}</th>
                                        <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                        <th class="text-filter">{{ __('translation.product.category') }}</th>
                                        <th class="text-filter">{{ __('translation.batch.stockQuantity') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="tabNote">
                            <table class="table table-bordered w-100 text-nowrap border-bottom"
                                id="purchaser_order_datatable">
                                <thead class="fixed-header-table">
                                    <tr>
                                        <th class="text-filter">{{ __('translation.batch.id') }}</th>
                                        <th class="no-sort">{{ __('translation.purchaseOrder.createdDate') }}</th>
                                        <th class="text-filter">{{ __('translation.purchaseOrder.scheduleDate') }}</th>
                                        <th class="text-filter">{{ __('translation.purchaseOrder.totalAmount') }}(CAD)</th>
                                        <th class="text-filter">{{ __('translation.purchaseOrder.status') }}</th>
                                    </tr>
                                </thead>
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
        let batch = @json($batch);
    </script>
    <script src="{{ asset('assets/js/page/batch/batch.detail.js') }}"></script>
@endSection()
