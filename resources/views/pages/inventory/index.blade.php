@extends('layouts/main')


@section('styles')
    <link href="{{ asset('assets/plugins/intl-tel-input/build/css/intlTelInput.min.css') }}" rel="stylesheet" />
    <style>
        .profile-img-main {
            position: relative;
        }

        .profile-photo-edit {
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            background-color: #fff;
            border-radius: 50%;
            margin-bottom: 0 !important;
            bottom: 0%;
            cursor: pointer;
            right: 0;
        }

        #profile-img-file-input {
            width: 0;
            height: 0;
        }

        #imagePreview {
            overflow: hidden;
        }

        #imagePreview {
            cursor: pointer;
        }

        #inventory_edit input:read-only {
            background-color: rgba(228, 231, 236, 0.35) !important;
        }

        #inventory_edit input:read-only:hover {
            cursor: default;
        }

        #inventory_edit input:read-only:focus {
            border-color: #eaedf1;
        }

        .ql-toolbar.ql-snow .ql-picker-label,
        .ql-snow.ql-toolbar button,
        .ql-snow .ql-toolbar button {
            height: 24px;
            line-height: 24px;
        }

        .ql-toolbar.ql-snow .ql-formats {
            margin-right: 15px;
        }

        .ql-snow .ql-picker.ql-header {
            width: 88px;
        }

        .ql-editor {
            min-height: 200px;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.product.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('product') }}">{{ __('translation.menu.product') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.product.information') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="pt-2 mb-4 m-0" style="border: 1px solid #eaedf1">
                        <label class="ps-4 fw-bold">{{ __('translation.product.information') }}</label>
                        <div class="p-1 row m-0">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 form-group">
                                                <label for="name">{{ __('translation.product.name') }}</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    value="{{ $product['name'] }}" disabled>
                                            </div>
                                            <div class="col-xl-12 col-lg-12 form-group">
                                                <label for="category_id">{{ __('translation.product.sku') }}</label>
                                                <input type="text" class="form-control" value="{{ $product['sku'] }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12 form-group">
                                                <label for="unit_id">{{ __('translation.product.unit') }}</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $product['unit_name'] }}" disabled>
                                            </div>
                                            <div class="col-xl-12 col-lg-12 form-group">
                                                <label for="category_id">{{ __('translation.product.category') }}</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $product['category_name'] }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 pb-4">
                                        <label for="description">{{ __('translation.product.description') }}</label>
                                        <textarea disabled class="form-control" name="description" id="description" cols="30" rows="5">{{ $product['description'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-body d-flex justify-content-center align-items-center"
                                    style="height: 85%;">
                                    <div class="align-items-center">
                                        <div class="col-lg-12 col-md-12">
                                            <div id ="product-image"
                                                class="d-flex flex-wrap align-items-center justify-content-center">
                                                <div class="profile-img-main">
                                                    <img src="{{ !empty($product['images'][0]['image_url']) ? $product['images'][0]['image_url'] : URL::asset('assets/images/add-image.png') }}"
                                                        id="imagePreview" alt="img"
                                                        class="m-0 p-1 border select-image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-menu-heading border-bottom-0">
                        <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                            <!-- Tabs -->
                            <nav id="navId" class="nav d-sm-flex d-block">
                                <p class="card-title">
                                    {{ __('translation.shelve.shelveList') }}
                                </p>
                            </nav>
                            <button class="btn btn-primary"
                                onclick="showAddInventoryModal()">{{ __('translation.add') }}</button>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body mb-5">
                        <div class="">
                            <div class="tab-pane active" id="tabShelve">
                                <table class="table table-bordered w-100 text-nowrap border-bottom"
                                    id="inventory_datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-filter">{{ __('translation.shelve.id') }}</th>
                                            <th class="text-filter">{{ __('translation.shelve.warehouse') }}</th>
                                            <th class="text-filter no-sort">{{ __('translation.shelve.location') }}</th>
                                            <th class="text-filter">{{ __('translation.product.supplier') }}</th>
                                            <th class="text-filter">{{ __('translation.shelve.stockQuantity') }}</th>
                                            <th class="text-filter">{{ __('translation.inventory.lastUpdated') }}</th>
                                            <th class="text-center no-sort">{{ __('translation.action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    @include('pages.inventory.log', ['logs' => $product['inventoryLogs']])
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-inventory-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content country-select-modal">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('translation.inventory.add') }}</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="card-body">
                    <form action="#" class="jquery-validate-form" id="add-product-inventory">
                        <div class="row">
                            <div class="col-xl-4 col-lg-12 form-group">
                                <label for="supplier_name">{{ __('translation.purchaseOrder.supplierName') }}<span
                                        class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="supplier_name" id="supplier_name"
                                    readonly required>
                            </div>
                            <div class="col-xl-8 col-lg-12 form-group">
                                <label for="warehouse_name">{{ __('translation.shelve.warehouse') }}<span
                                        class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="warehouse_name" id="warehouse_name"
                                    readonly required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                                <!-- Tabs -->
                                <nav id="navId" class="nav d-sm-flex d-block">
                                    <p class="card-title">
                                        {{ __('translation.shelve.shelveList') }}
                                    </p>
                                </nav>
                                <button class="btn btn-primary" type="button"
                                    onclick="selectShelves()">{{ __('translation.add') }}</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom w-100" id="inventory-add-table">
                                <thead class="fixed-header-table">
                                    <tr>
                                        <th>{{ __('translation.shelve.id') }}</th>
                                        <th>{{ __('translation.shelve.name') }}</th>
                                        <th>{{ __('translation.shelve.location') }}</th>
                                        <th>{{ __('translation.inventory.quantity') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                            <button class="btn btn-danger" type="button"
                                data-bs-dismiss="modal">{{ __('translation.button.cancel') }}</button>
                            <button class="btn btn-primary" type="button"
                                onclick="confirmAddInventory()">{{ __('translation.button.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade delete-modal" id="confirmModal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModal_title">{{ __('translation.modal.confirmBack') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="confirmModal_body">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-dismiss="modal">{{ __('translation.no') }}</button>
                    <button class="btn btn-primary" id="confirmModalSubmitBtn">{{ __('translation.yes') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->
@endSection()

@section('scripts')
    <script>
        const productId = {{ $product->id }};
    </script>
    <script src="{{ asset('assets/js/page/inventory/inventory.product.js') }}"></script>
    <script src="{{ asset('assets/js/page/inventory/inventory.create.js') }}"></script>
    <script src="{{ asset('assets/plugins/quill/quill.min.js') }}"></script>
@endSection()
