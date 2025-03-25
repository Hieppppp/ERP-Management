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

        #product-datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 40vh !important;
            overflow: auto !important;
        }

        #product-datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
            border-collapse: separate !important;
        }

        .fiexd-header {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 4;
        }
    </style>
@endsection()
@endsection()

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('translation.supplier.management') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('supplier') }}">{{ __('translation.menu.supplier') }}</a></li>
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
                <h3 class="card-title">{{ __('translation.supplier.detail') }}</h3>
            </div>
            <div class="card-body">
                <div class="pt-2 mb-4 m-0" style="border: 1px solid #eaedf1">
                    <label class="ps-4 fw-bold">{{ __('translation.supplier.information') }}</label>
                    <div class="d-flex p-1">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="name">{{ __('translation.supplier.name') }}</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $supplier['name'] }}" disabled>
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="email">{{ __('translation.supplier.email') }}</label>
                                    <input type="text" class="form-control" name="email" id="email" disabled
                                        value="{{ $supplier['email'] }}">
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="phone_number">{{ __('translation.supplier.phoneNumber') }}</label>
                                    <input type="text" class="form-control phone" name="phone_number"
                                        id="phone_number" disabled placeholder="(XXX)XXXX-XXX">
                                </div>
                                @if ($supplier['site'])
                                    <div class="col-xl-6 col-lg-12 form-group">
                                        <label for="site">{{ __('translation.supplier.contactURL') }}</label>
                                        <input type="text" class="form-control" name="site" id="site"
                                            value="{{ $supplier['site'] }}" disabled>
                                    </div>
                                @endif
                                <div class=" col-xl-12 col-lg-12 form-group">
                                    <label for="detail_address">{{ __('translation.supplier.address') }}</label>
                                    <input type="text" class="form-control" name="detail_address" id="detail_address"
                                        value="{{ $supplier['address'] }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="name">{{ __('translation.supplier.logo') }}</label>
                            <div class="d-flex flex-wrap align-items-center justify-content-center flex-column">
                                <div class="profile-img-main">
                                    <img src="{{ $supplier->logo_url ?? URL::asset('assets/images/no-image.png') }}"
                                        id="imagePreview" alt="img"
                                        class="m-0 p-1 border select-image img-overlay-light-box">
                                </div>
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                    name="logo" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="" class="fw-bold">{{ __('translation.product.list') }}</label>
                    <table class="table table-bordered w-100 text-nowrap border-bottom" id="product-datatable">
                        <thead class="fiexd-header">
                            <tr>
                                <th class="text-filter">#</th>
                                <th class="no-sort">{{ __('translation.product.image') }}</th>
                                <th class="text-filter">{{ __('translation.product.name') }}</th>
                                <th class="text-filter">SKU</th>
                                <th class="text-filter">{{ __('translation.product.quantity') }}</th>
                                <th class="text-filter">{{ __('translation.product.unitPrice') }}</th>
                                <th class="text-filter">{{ __('translation.supplier.unitCost') }}</th>
                                <th class="text-filter">{{ __('translation.product.category') }}</th>
                                <th class="text-filter">{{ __('translation.product.unit') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @include('pages.supplier.log', ['logs' => $activities])
            </div>
        </div>
    </div>
</div>
<!-- END ROW -->
@endSection()

@section('scripts')
<script>
    const supplier = <?php echo json_encode($supplier); ?>;
    const products = <?php echo json_encode($products); ?>;
</script>
<script src="{{ asset('assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('assets/js/page/supplier/supplier.detail.js') }}"></script>
@endSection()
