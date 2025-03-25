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
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.warehouse.list') }}</h3>
                    <button class="btn btn-primary"
                        onclick="showCreateModal()">{{ __('translation.formTitle.warehouseCreate') }}</button>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-bordered text-nowrap border-bottom w-100" id="warehouse-datatable">
                        <thead>
                            <tr>
                                <th class="text-filter">#</th>
                                <th class="text-filter">{{ __('translation.warehouse.name') }}</th>
                                <th class="text-filter">{{ __('translation.warehouse.address') }}</th>
                                <th>{{ __('translation.warehouse.numberOfShelves') }}</th>
                                <th class="text-filter">{{ __('translation.warehouse.contact') }}</th>
                                <th class="text-filter">{{ __('translation.warehouse.lastUpdatedDate') }}</th>
                                <th class="no-sort">{{ __('translation.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade delete-modal" id="deleteWarehouse" tabindex="-1" role="dialog">
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
    <script src="{{ asset('assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/warehouse/warehouse.datatable.js') }}"></script>
@endSection()
