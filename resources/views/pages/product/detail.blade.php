@extends('layouts/main')


@section('styles')
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

    .iti {
        display: block !important;
    }

    #profile-img-file-input-error {
        display: flex;
        justify-content: center;
    }

    .select2-selection__choice {
        margin-top: 6px !important;
    }

    .select2-container--default.select2-container--disabled .select2-selection--multiple {
        background-color: rgba(228, 231, 236, 0.35) !important;
    }

    .code-upc {
        padding-left: 10px;
        padding-right: 10px;
        max-height: 75px;
        border: 1px solid #eaedf1;
    }

    .heigt-lable-code-upc {
        height: 75px;
    }
</style>
@endsection()
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
                <h3 class="card-title">{{ __('translation.product.detail') }}</h3>
            </div>
            <div class="card-body">
                <div class="pt-2 mb-4 m-0" style="border: 1px solid #eaedf1">
                    <label class="ps-4 fw-bold">{{ __('translation.product.information') }}</label>
                    <div>
                        <label class="m-4 heigt-lable-code-upc">
                            <p class="code-upc">{{ $product['sku'] }}</p>
                        </label>
                    </div>
                    <div class="p-1 row m-0">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="name">{{ __('translation.product.name') }}</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $product['name'] }}" disabled>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="max_quantity">{{ __('translation.product.maxQuantity') }}</label>
                                            <input type="number" class="form-control" name="max_quantity" id="max_quantity" value="{{ $product['max_quantity'] }}" disabled>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="min_quantity">{{ __('translation.product.minQuantity') }}</label>
                                            <input type="number" class="form-control" name="min_quantity" disabled id="min_quantity" value="{{ $product['min_quantity'] }}">
                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="quantity">{{ __('translation.product.quantity') }}</label>
                                            <input type="number" class="form-control" name="quantity" disabled id="quantity" value="{{ $product['quantity'] ?? 0 }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xl-6">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="unit_id">{{ __('translation.product.unit') }}</label>
                                            <input type="text" class="form-control" value="{{ $product['unit_name'] }}" disabled>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="category_id">{{ __('translation.product.category') }}</label>
                                            <input type="text" class="form-control" value="{{ $product['category_name'] }}" disabled>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="unit_price">{{ __('translation.product.unitPrice') }}
                                                (CAD)</label>
                                            <input type="number" class="form-control" name="unit_price" id="unit_price" value="{{ $product['unit_price'] }}" disabled>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 form-group">
                                            <label for="category_id">{{ __('translation.product.sku') }}</label>
                                            <input type="text" class="form-control" value="{{ $product['sku'] }}" disabled>
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
                            <label for="name">{{ __('translation.product.uploadImage') }}</label>
                            <div class="card-body d-flex justify-content-center align-items-center" style="height: 85%;">
                                <div class="align-items-center">
                                    <div class="col-lg-12 col-md-12">
                                        <div id="product-image" class="d-flex flex-wrap align-items-center justify-content-center">
                                            <div class="profile-img-main">
                                                <img src="{{ !empty($product['images'][0]['image_url']) ? $product['images'][0]['image_url'] : URL::asset('assets/images/add-image.png') }}" id="imagePreview" alt="img" class="m-0 p-1 border select-image">
                                                <input id="profile-img-file-input" type="file" class="profile-img-file-input" name="image[]" accept="image/*">
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
                            <a class="nav-link border border-bottom-0 br-sm-5 active" data-bs-toggle="tab" href="#tabParentProduct" id="tabParentProductLink">
                                {{ __('translation.product.parentProduct') }}
                            </a>
                            {{-- <a class="nav-link border border-bottom-0 br-sm-5" data-bs-toggle="tab"
                                href="#tabChildProduct" id="tabChildProductLink">
                                {{ __('translation.product.childProduct') }}
                            </a> --}}
                            <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab" href="#tabSupplier" id="tabSupplierLink">
                                {{ __('translation.product.supplier') }}
                            </a>
                            <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab" href="#tabShelve" id="tabShelveLink">
                                {{ __('translation.shelve.shelve') }}
                            </a>
                        </nav>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabParentProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="parent_product_datatable">
                                <thead>
                                    <tr>
                                        <th class="text-filter">#</th>
                                        <th class="text-filter">{{ __('translation.product.image') }}</th>
                                        <th class="text-filter">{{ __('translation.product.name') }}</th>
                                        <th class="text-filter">{{ __('translation.product.category') }}</th>
                                        <th class="text-filter">{{ __('translation.product.unitPrice') }} (CAD)</th>
                                        <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                        <th class="text-filter">{{ __('translation.product.quantity') }}</th>
                                        <th class="text-filter">{{ __('translation.product.maxQuantity') }}</th>
                                        <th class="text-filter">{{ __('translation.product.minQuantity') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="tabChildProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="child_product_datatable">
                                <thead>
                                    <tr>
                                        <th class="text-filter">#</th>
                                        <th class="text-filter">{{ __('translation.product.image') }}</th>
                                        <th class="text-filter">{{ __('translation.product.name') }}</th>
                                        <th class="text-filter">{{ __('translation.product.category') }}</th>
                                        <th class="text-filter">{{ __('translation.product.unitPrice') }} (CAD)</th>
                                        <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                        <th class="text-filter">{{ __('translation.product.quantity') }}</th>
                                        <th class="text-filter">{{ __('translation.product.maxQuantity') }}</th>
                                        <th class="text-filter">{{ __('translation.product.minQuantity') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="tabSupplier">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="supplier_datatable">
                                <thead>
                                    <tr>
                                        <th class="text-filter">#</th>
                                        <th class="no-sort">{{ __('translation.supplier.logo') }}</th>
                                        <th class="text-filter">{{ __('translation.supplier.name') }}</th>
                                        <th class="text-filter">{{ __('translation.supplier.email') }}</th>
                                        <th class="text-filter">{{ __('translation.supplier.phoneNumber') }}</th>
                                        <th class="text-filter">{{ __('translation.supplier.address') }}</th>
                                        <th class="text-filter">{{ __('translation.supplier.unitCost') }} (CAD)</th>
                                        <th class="text-filter">{{ __('translation.product.supplierSku') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="tabShelve">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="shelve_datatable">
                                <thead>
                                    <tr>
                                        <th class="text-filter">{{ __('translation.shelve.id') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.shelveName') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.warehouse') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.location') }}</th>
                                        <th class="text-filter">{{ __('translation.shelve.stockQuantity') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @include('pages.product.log', ['logs' => $activityLogs])
            </div>
        </div>
    </div>
</div>
<!-- END ROW -->
@endSection()

@section('scripts')
<script>
    const productDataInDatabase = <?php echo json_encode($product); ?>;
</script>
<script src="{{ asset('assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('assets/js/page/product/product.detail.js') }}"></script>
@endSection()