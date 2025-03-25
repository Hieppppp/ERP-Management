@extends('layouts/main')


@section('styles')
    <link href="{{ asset('assets/plugins/intl-tel-input/build/css/intlTelInput.min.css') }}" rel="stylesheet" />
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.warehouse.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('warehouse') }}">{{ __('translation.menu.warehouse') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.detail') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.warehouse.detail') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row pt-2 mb-4 m-0" style="border: 1px solid #eaedf1">
                        <label for="" class="fw-bold">{{ __('translation.warehouse.information') }}</label>
                        <div class="col-xl-4 form-group">
                            <label for="name">{{ __('translation.warehouse.name') }}</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $warehouse['name'] }}" disabled>
                        </div>
                        <div class="col-xl-4 form-group">
                            <label for="detail_address">{{ __('translation.warehouse.address') }}</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $warehouse->address }}" disabled>
                        </div>
                        <div class="col-xl-4 form-group d-flex flex-column">
                            <label for="detail_address">{{ __('translation.warehouse.contact') }}</label>
                            <input type="text" class="form-control phone" name="phone_number_update"
                                id="phone_number_update" required placeholder="(XXX)XXXX-XXX" disabled>
                        </div>
                    </div>
                    <div>
                        <label for="" class="fw-bold">{{ __('translation.shelve.list') }}</label>
                        <table class="table table-bordered w-100 text-nowrap border-bottom" id="shelve-datatable">
                            <thead>
                                <tr>
                                    <th class="text-filter">#</th>
                                    <th class="text-filter">{{ __('translation.shelve.shelveName') }}</th>
                                    <th class="text-filter">{{ __('translation.shelve.location') }}</th>
                                    <th class="text-filter">{{ __('translation.shelve.productQuantity') }}</th>
                                    <th class="text-filter">{{ __('translation.warehouse.lastUpdatedDate') }}</th>
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
    <script src="{{ asset('assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
    <script>
        let warehouse = @json($warehouse);
    </script>
    <script src="{{ asset('assets/js/page/warehouse/warehouse.detail.js') }}"></script>
@endSection()
