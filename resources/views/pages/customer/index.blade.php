@extends('layouts/main')


@section('styles')
<style>
    .min-w-image {
        min-width: 60px;
    }
</style>
@endsection()

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('translation.customer.management') }}</h1>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between">
                <h3 class="card-title">{{ __('translation.customer.customerList') }}</h3>
                <a class="btn btn-primary" href="{{ url('customer/create') }}">{{ __('translation.formTitle.customerCreate') }}</a>
            </div>
            <div class="card-body overflow-auto">
                <table class="table w-100 table-bordered text-nowrap border-bottom" id="customer-datatable">
                    <thead>
                        <tr>
                            <th class="text-filter">#</th>
                            <th class="no-sort">{{ __('translation.customer.avatar') }}</th>
                            <th class="text-filter">{{ __('translation.customer.name') }}</th>
                            <th class="text-filter">{{ __('translation.customer.email') }}</th>
                            <th class="text-filter">{{ __('translation.customer.phoneNumber') }}</th>
                            <th class="text-filter">{{ __('translation.customer.address') }}</th>
                            <th class="text-filter">{{ __('translation.customer.create_at') }}</th>
                            <th class="no-sort">{{ __('translation.action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade delete-modal" id="deleteCustomer" tabindex="-1" role="dialog">
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
<script src="{{ asset('assets/js/page/customer/customer.datatable.js') }}"></script>
@endSection()