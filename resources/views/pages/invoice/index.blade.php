@extends('layouts/main')


@section('styles')
    <style>
        .width-status {
            width: 100px;
            font-size: 10px;
        }

        .draft {
            border: 1px solid #C4C4C4 !important;
            color: #C4C4C4 !important;
        }

        .partially_paid {
            border: 1px solid #FF9800 !important;
            color: #FF9800 !important;
        }

        .paid {
            border: 1px solid #82C035 !important;
            color: #82C035 !important;
        }

        .posted {
            border: 1px solid #2C7865 !important;
            color: #2C7865 !important;
        }

        .unpaid {
            border: 1px solid #B03C3C !important;
            color: #B03C3C !important;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.invoice.management') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h3 class="card-title">{{ __('translation.invoice.list') }}</h3>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-bordered text-nowrap border-bottom w-100" id="invoice-datatable">
                        <thead class="fiexd-header">
                            <tr>
                                <th class="text-filter">{{ __('translation.invoice.id') }}</th>
                                <th class="text-filter">{{ __('translation.invoice.customerName') }}</th>
                                <th class="text-filter">{{ __('translation.invoice.invoiceDate') }} </th>
                                <th class="no-sort">{{ __('translation.invoice.scheduledDate') }}</th>
                                <th>{{ __('translation.invoice.totalAmount') }} (CAD)</th>
                                <th class="select-filter no-sort" id="payment_status">
                                    {{ __('translation.invoice.payment') }}</th>
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
    <script src="{{ asset('assets/js/page/invoice/invoice.datatable.js') }}"></script>
@endSection()
