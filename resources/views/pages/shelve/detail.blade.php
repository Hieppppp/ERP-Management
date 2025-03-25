@extends('layouts/main')


@section('styles')
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.shelve.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('shelve') }}">{{ __('translation.shelve.shelve') }}</a></li>
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
                    <h3 class="card-title">{{ __('translation.shelve.detail') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row pt-2 mb-4 m-0" style="border: 1px solid #eaedf1">
                        <label for="" class="fw-bold">{{ __('translation.shelve.information') }}</label>
                        <div class="col-xl-6 form-group">
                            <label for="name">{{ __('translation.shelve.name') }}</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $shelve['name'] }}" disabled>
                        </div>
                        <div class="col-xl-6 form-group">
                            <label for="warehouse_id">{{ __('translation.shelve.warehouse') }}</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $shelve['warehouses']['name'] ?? '' }}" disabled>
                        </div>
                        <div class="col-xl-12 form-group">
                            <label for="location">{{ __('translation.shelve.location') }}</label>
                            <textarea class="form-control" name="location" id="location" cols="30" rows="5" disabled>{{ $shelve['location'] }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label for="" class="fw-bold">{{ __('translation.product.list') }}</label>
                        <table class="table table-bordered w-100 text-nowrap border-bottom" id="shelve-datatable">
                            <thead>
                                <tr>
                                    <th class="text-filter">#</th>
                                    <th class="no-sort">{{ __('translation.product.image') }}</th>
                                    <th class="text-filter">{{ __('translation.product.name') }}</th>
                                    <th class="text-filter">{{ __('translation.product.category') }}</th>
                                    <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                    <th class="text-filter">{{ __('translation.product.quantity') }}</th>
                                    <th class="text-filter">{{ __('translation.shelve.batchNumber') }}</th>
                                    <th class="text-filter">{{ __('translation.shelve.receivedDate') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END ROW -->
@endSection()

@section('scripts')
    <script>
        const products = @json($products);
    </script>
    <script src="{{ asset('assets/js/page/shelve/shelve.detail.js') }}"></script>
@endSection()
