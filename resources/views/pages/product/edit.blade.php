@extends('layouts/main')


@section('styles')
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

        .selected-row {
            background-color: yellow;
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
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.edit') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom">
            <h3 class="card-title">{{ __('translation.product.edit') }}</h3>
        </div>
        <div class="card-body">
            <form id="product_edit" class="jquery-validate-form" method="POST" action="{{ url('product') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="text" name="productIdInDatabase" id="productIdInDatabase" value="{{ $product['id'] }}"
                    hidden>
                <div class="row pt-2 mb-4 m-0" style="border: 1px solid #eaedf1;">
                    <div class="col-lg-6 col-sm-12 row">
                        <div class="col-xl-6 col-lg-12">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <label for="name">{{ __('translation.product.name') }}<span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $product['name'] }}">
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <label for="max_quantity">{{ __('translation.product.maxQuantity') }}<span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="number" class="form-control" name="max_quantity" id="max_quantity"
                                        value="{{ $product['max_quantity'] }}">
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <label for="min_quantity">{{ __('translation.product.minQuantity') }}<span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="number" class="form-control" name="min_quantity" id="min_quantity"
                                        value="{{ $product['min_quantity'] }}">
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-6 col-lg-12">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="unit_id">{{ __('translation.product.unit') }}<span
                                                class="text-danger">
                                                *</span></label>
                                        @if (PermissionRole::checkPermission([Permission::UNIT]))
                                            <button type="button" class="add_new_item"
                                                onclick="showModalCreate('unit')"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-plus-circle plus-down-add">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16">
                                                    </line>
                                                    <line x1="8" y1="12" x2="16" y2="12">
                                                    </line>
                                                </svg><label>{{ __('translation.add_new') }}</label></button>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column-reverse">
                                        <select class="form-control select2-show-search form-select" name="unit_id"
                                            id="unit_id" required> </select>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="category_id">{{ __('translation.product.category') }}<span
                                                class="text-danger">
                                                *</span></label>
                                        @if (PermissionRole::checkPermission([Permission::CATEGORY]))
                                            <button type="button" class="add_new_item"
                                                onclick="showModalCreate('category')"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-plus-circle plus-down-add">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16">
                                                    </line>
                                                    <line x1="8" y1="12" x2="16" y2="12">
                                                    </line>
                                                </svg><label>{{ __('translation.add_new') }}</label></button>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column-reverse">
                                        <select class="form-control select2-show-search form-select" name="category_id"
                                            id="category_id" required> </select>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <label for="unit_price">{{ __('translation.product.unitPrice') }} (CAD)<span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="number" class="form-control" name="unit_price" id="unit_price"
                                        value="{{ $product['unit_price'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            <label for="description">{{ __('translation.product.description') }}</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="5">{{ $product['description'] }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 row">
                        <label for="name">{{ __('translation.product.uploadImage') }}</label>
                        <div class="card-body">
                            <div class="align-items-center">
                                <div class="col-lg-12 col-md-12">
                                    <div id ="product-image"
                                        class="d-flex flex-wrap align-items-center justify-content-center">
                                        <div class="profile-img-main">
                                            <img src="{{ !empty($product['images'][0]['image_url']) ? $product['images'][0]['image_url'] : URL::asset('assets/images/add-image.png') }}"
                                                id="imagePreview" alt="img" class="m-0 p-1 border select-image">
                                            <input id="profile-img-file-input" type="file"
                                                class="profile-img-file-input" name="image[]" accept="image/*">
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
                            <a class="nav-link border border-bottom-0 br-sm-5 active" data-bs-toggle="tab"
                                href="#tabParentProduct" id="tabParentProductLink">
                                {{ __('translation.product.parentProduct') }}
                            </a>
                            {{-- <a class="nav-link border border-bottom-0 br-sm-5" data-bs-toggle="tab"
                                href="#tabChildProduct" id="tabChildProductLink">
                                {{ __('translation.product.childProduct') }}
                            </a> --}}
                            <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab"
                                href="#tabSupplier" id="tabSupplierLink">
                                {{ __('translation.product.supplier') }}
                            </a>
                        </nav>
                        <button class="btn btn-primary" type="button"
                            onclick="selectModal()">{{ __('translation.add') }}</button>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabParentProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom"
                                id="parent_product_datatable">
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
                                        <th class="no-sort">{{ __('translation.action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="tabChildProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom"
                                id="child_product_datatable">
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
                                        <th class="no-sort">{{ __('translation.action') }}</th>
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
                                        <th class="no-sort">{{ __('translation.action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger me-4" type="button"
                        onclick="back()">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary" type="button"
                        onclick="productEdit()">{{ __('translation.button.submit') }}</button>
                </div>
            </form>
            @include('pages.product.log', ['logs' => $activityLogs])
        </div>
    </div>

    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        let productDataInDatabase = <?php echo json_encode($product); ?>;
        $('#profile-img-file-input').on('change', function() {
            const file = this.files[0];
            if (!file) {
                return;
            }
            if (!file.type.startsWith('image/') || !/\.(jpeg|png|jpg|gif|svg)$/i.test(file.name)) {
                $(this).val('');
                $('#imagePreview').attr('src', "{{ asset('assets/images/add-image.png') }}");
                notification("error", trans("validation.importPhotos", {
                    field: 'jpeg, png, jpg, gif, svg'
                }));
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                notification('error', trans("validation.largerThanFile", {
                    field: 5
                }));
                $('#imagePreview').attr('src', "{{ asset('assets/images/add-image.png') }}");
                $(this).val('');
                return;
            }
            const reader = new FileReader();

            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            };

            reader.readAsDataURL(file);
        });
    </script>
    <script src="{{ asset('assets/js/page/product/product.edit.js') }}"></script>
@endSection()
