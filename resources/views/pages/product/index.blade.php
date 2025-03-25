@extends('layouts/main')


@section('styles')
    <style>
        #filter-container .select2-container {
            width: 100% !important;
        }

        #product-datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            min-height: 40vh !important;
        }

        .color-lable {
            width: 20px;
            height: 20px;
        }

        .stock-indicator {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            width: 100%;
        }

        .indicator-lable {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            width: 45%;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .red {
            background-color: #B03C3C;
        }

        .orange {
            background-color: #FF9800;
        }

        .yellow {
            background-color: #FFD600;
        }

        .black {
            background-color: #000000;
        }

        @media (max-width: 768px) {
            .indicator-lable {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .dot {
                width: 10px;
                height: 10px;
            }

            .indicator-lable span {
                font-size: 14px;
            }
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.product.management') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <button class="btn border" type="button" id="filter-btn" onclick="openSearchFilter()"><i
                            class="fe fe-filter text-light"></i> {{ __('translation.button.search') }}</button>
                    <a class="btn btn-primary" href="{{ url('product/create') }}">{{ __('translation.product.create') }}</a>
                </div>
                <div class="card-body overflow-auto">
                    <div class="stock-indicator">
                        <div class="indicator-lable">
                            <span class="dot red"></span>
                            <span>{{ __('translation.product.label-red') }}</span>
                        </div>
                        <div class="indicator-lable">
                            <span class="dot yellow"></span>
                            <span>{{ __('translation.product.label-yellow') }}</span>
                        </div>
                        <div class="indicator-lable">
                            <span class="dot orange"></span>
                            <span>{{ __('translation.product.label-orange') }}</span>
                        </div>
                        <div class="indicator-lable">
                            <span class="dot black"></span>
                            <span>{{ __('translation.product.label-black') }}</span>
                        </div>
                    </div>
                    <table class="table table-bordered text-nowrap border-bottom w-100" id="product-datatable">
                        <thead>
                            <tr>
                                <th class="text-filter">#</th>
                                <th class="no-sort">{{ __('translation.product.image') }}</th>
                                <th class="text-filter">{{ __('translation.product.name') }}</th>
                                <th class="text-filter">{{ __('translation.product.sku') }}</th>
                                <th class="text-filter">{{ __('translation.product.category') }}</th>
                                <th class="text-filter number-filter">{{ __('translation.product.unitPrice') }} (CAD)</th>
                                <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                <th class="">{{ __('translation.product.quantity') }}</th>
                                <th class="no-sort">{{ __('translation.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade delete-modal" id="deleteProduct" tabindex="-1" role="dialog">
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

    <div class="modal fade" id="product-filter">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content country-select-modal">
                <div class="modal-header">
                    <h6 class="modal-title text-bold">{{ __('translation.product.filter.title') }}</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <h6 class="modal-title">{{ __('translation.product.filter.subTitle') }} :</h6>
                    <div id="filter-container" class="mt-2 mb-3">

                    </div>
                    <button class="btn btn-primary" id="add-rule">{{ __('translation.button.addRule') }}</button>
                </div>
                <div class="modal-footer justify-content-start">
                    <button class="btn btn-danger" data-bs-dismiss="modal">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary" id="filter-product-btn">{{ __('translation.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->
@endSection()

@section('scripts')
    <script src="{{ asset('assets/js/page/product/product.datatable.js') }}"></script>
@endSection()
