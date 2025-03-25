@extends('layouts/main')


@section('styles')
    <style>
        #log-datatable ul {
            list-style-type: disc;
            padding-left: 2rem;
            font-size: small;
        }

        #log-datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 100vh !important;
            overflow: auto !important;
        }

        #log-datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
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
            <h1 class="page-title">{{ __('translation.log.logManagement') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.log.logList') }}</h3>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-bordered text-nowrap border-bottom w-100" id="log-datatable">
                        <thead class="fiexd-header">
                            <tr>
                                <th class="text-filter">{{ __('translation.log.logId') }}</th>
                                <th class="text-filter">{{ __('translation.log.createdDate') }}</th>
                                <th class="select-filter no-sort" id="module">{{ __('translation.log.module') }}</th>
                                <th class="text-filter no-sort">{{ __('translation.log.user') }}</th>
                                <th class="select-filter no-sort" id="action">{{ __('translation.log.action') }}</th>
                                <th class="no-sort">{{ __('translation.log.description') }}</th>
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
    <script src="{{ asset('assets/js/page/log/log.datatable.js') }}"></script>
@endSection()
