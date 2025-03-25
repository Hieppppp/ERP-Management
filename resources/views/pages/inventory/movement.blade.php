@extends('layouts/main')


@section('styles')
    <style>
        .dataTables_wrapper .row:nth-of-type(2)>.col-sm-12 {
            min-height: 0vh !important;
        }

        #shelve-add-table_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 40vh !important;
            overflow: auto !important;
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
            <h1 class="page-title">{{ __('translation.product.movement') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom">
            <h3 class="card-title">{{ __('translation.formTitle.supplierCreate') }}</h3>
        </div>
        <div class="card-body">
            <form id="movement" class="jquery-validate-form" method="POST" action="{{ url('supplier') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="warehouse">{{ __('translation.shelve.warehouse') }}</label>
                        <input type="text" class="form-control" name="warehouse" id="warehouse" disabled
                            value="{{ $productLocation->shelve->warehouses->name }}">
                    </div>
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="location">{{ __('translation.shelve.location') }}</label>
                        <input type="text" class="form-control" name="location" id="location" disabled
                            value="{{ $productLocation->shelve->location }}">
                    </div>
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="stockLocation">{{ __('translation.shelve.stockQuantity') }}</label>
                        <input type="text" class="form-control" name="stockLocation" id="stockLocation" disabled
                            value="{{ $productLocation->quantity }}">
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="form-group d-flex justify-content-between">
                        <div class="d-flex align-items-end">
                            {{ __('translation.product.destinationShelve') }}
                        </div>
                        <div>
                            <button class="btn btn-primary" type="button"
                                onclick="addShelve()">{{ __('translation.shelve.addShelve') }}</button>
                        </div>
                    </div>
                    <table class="table table-bordered text-nowrap border-bottom w-100" id="shelve-add-table">
                        <thead class="fiexd-header">
                            <tr>
                                <th>{{ __('translation.shelve.id') }}</th>
                                <th>{{ __('translation.shelve.name') }}</th>
                                <th>{{ __('translation.shelve.location') }}</th>
                                <th>{{ __('translation.shelve.stockQuantity') }}</th>
                                <th>{{ __('translation.shelve.quantityMoved') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="form-group mt-2">
                    <button class="btn btn-danger me-4" type="button"
                        onclick="back()">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary" type="button"
                        onclick="movement()">{{ __('translation.button.create') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const productLocation = @json($productLocation ?? '');
    </script>
    <script src="{{ asset('assets/js/page/inventory/movement.js') }}"></script>
@endSection()
