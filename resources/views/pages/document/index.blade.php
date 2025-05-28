@extends('layouts/main')


@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('translation.document.management') }}</h1>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between">
                <h3 class="card-title">{{ __('translation.document.documentList') }}</h3>
                <a class="btn btn-primary" href="{{ url('document/create') }}">{{ __('translation.document.create') }}</a>
            </div>
            <div class="card-body overflow-auto">
                <table class="table w-100 table-bordered text-nowrap border-bottom" id="document-datatable">
                    <thead>
                        <tr>
                            <th class="text-filter">#</th>
                            <th class="text-filter">{{ __('translation.document.fileName') }}</th>
                            <th class="text-filter">{{ __('translation.document.fileHash') }}</th>
                            <th class="text-filter">{{ __('translation.document.documentType') }}</th>
                            <th class="text-filter">{{ __('translation.document.uploadBy') }}</th>
                            <th class="text-filter">{{ __('translation.document.create_at') }}</th>
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
<script src="{{ asset('assets/js/page/document/document.datatable.js') }}"></script>
@endSection()
