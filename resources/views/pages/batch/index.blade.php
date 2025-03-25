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
            <h1 class="page-title">{{ __('translation.batch.management') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.batch.list') }}</h3>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-bordered w-100 text-nowrap border-bottom" id="batch-datatable">
                        <thead>
                            <tr>
                                <th class="text-filter">{{ __('translation.batch.id') }}</th>
                                <th class="text-filter">{{ __('translation.batch.receivedDate') }}</th>
                                <th class="text-filter">{{ __('translation.batch.supplierName') }}</th>
                                <th class="text-filter">{{ __('translation.batch.numberOfProduct') }}</th>
                                <th class="text-filter">{{ __('translation.batch.storageLocation') }}</th>
                                <th class="no-sort">{{ __('translation.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->
@endSection()

@section('scripts')
    <script src="{{ asset('assets/js/page/batch/batch.datatable.js') }}"></script>
@endSection()
