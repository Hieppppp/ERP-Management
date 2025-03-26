@extends('layouts/main')


@section('styles')
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.department.management') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.department.list') }}</h3>
                    <button class="btn btn-primary"
                        onclick="showCreateModal()">{{ __('translation.department.create') }}</button>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-bordered w-100 text-nowrap border-bottom" id="department-datatable">
                        <thead>
                            <tr>
                                <th class="text-filter">#</th>
                                <th class="text-filter">{{ __('translation.department.name') }}</th>
                                <th class="text-filter">{{ __('translation.department.description') }}</th>
                                <th class="no-sort">{{ __('translation.department.employeeQuantity') }}</th>
                                <th class="no-sort">{{ __('translation.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade delete-modal" id="deleteDepartment" tabindex="-1" role="dialog">
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
    <script src="{{ asset('assets/js/page/department/department.datatable.js') }}"></script>
@endSection()
