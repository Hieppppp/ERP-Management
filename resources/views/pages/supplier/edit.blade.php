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

        .iti {
            display: block !important;
        }

        #profile-img-file-input-error {
            display: flex;
            justify-content: center;
        }
    </style>
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
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.edit') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom">
            <h3 class="card-title">{{ __('translation.formTitle.supplierEdit') }}</h3>
        </div>
        <div class="card-body">
            <form id="supplier_update" class="jquery-validate-form" method="POST"
                action="{{ route('supplier.update', $supplier['id']) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="text" name="supplierId" id="supplierId" value="{{ $supplier['id'] }}" hidden>
                <div class="row">
                    <div class="col-md-6 col-sm-12 row">
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="name">{{ __('translation.supplier.name') }}<span class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $supplier['name'] }}" required>
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="email">{{ __('translation.supplier.email') }}<span class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="email" id="email" required
                                value="{{ $supplier['email'] }}">
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="phone_number">{{ __('translation.supplier.phoneNumber') }}<span
                                    class="text-danger"> *</span></label>
                            <input type="text" class="form-control phone" name="phone_number" id="phone_number" required
                                placeholder="(XXX)XXXX-XXX">
                            <input type="text" class="form-control phone" name="phone" id="phone" hidden
                                value={{ $supplier['phone'] }}>
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="site">{{ __('translation.supplier.contactURL') }}</label>
                            <input type="text" class="form-control" name="site" id="site"
                                value="{{ $supplier['site'] }}">
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <div class="d-flex justify-content-between">
                                <label for="country">{{ __('translation.supplier.country') }}<span class="text-danger">
                                        *</span></label>
                                <button type="button" class="add_new_item" onclick="showModalCreate('country')"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg><label>{{ __('translation.add_new') }}</label></button>
                            </div>
                            <div class="d-flex flex-column-reverse">
                                <select class="form-control select2-show-search form-select" name="country" id="country"
                                    required> </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <div class="d-flex justify-content-between">
                                <label for="country">{{ __('translation.supplier.province') }}<span class="text-danger">
                                        *</span></label>
                                <button type="button" class="add_new_item" onclick="showModalCreate('province')"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-plus-circle plus-down-add">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg><label>{{ __('translation.add_new') }}</label></button>
                            </div>
                            <div class="d-flex flex-column-reverse">
                                <select class="form-control select2-show-search form-select" name="province"
                                    id="province"></select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <div class="d-flex justify-content-between">
                                <label for="country">{{ __('translation.supplier.city') }}<span class="text-danger">
                                        *</span></label>
                                <button type="button" class="add_new_item" onclick="showModalCreate('city')"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-plus-circle plus-down-add">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg><label>{{ __('translation.add_new') }}</label></button>
                            </div>
                            <div class="d-flex flex-column-reverse">
                                <select class="form-control select2-show-search form-select" name="city"
                                    id="city"></select>
                            </div>
                        </div>
                        <div class=" col-xl-6 col-lg-12 form-group">
                            <label for="detail_address">{{ __('translation.supplier.address') }}<span
                                    class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="detail_address" id="detail_address"
                                value="{{ $supplier['detail_address'] }}">
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="postal_code">{{ __('translation.supplier.postalCode') }}<span
                                    class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="postal_code" id="postal_code"
                                value="{{ $supplier['postal_code'] }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 row">
                        <label for="name">{{ __('translation.supplier.logo') }}</label>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-12 col-md-12">
                                    <div class="d-flex flex-wrap align-items-center justify-content-center flex-column">
                                        <div class="profile-img-main">
                                            <img src="{{ $supplier->logo_url ?? URL::asset('assets/images/add-image.png') }}"
                                                id="imagePreview" alt="img" class="m-0 p-1 border select-image">
                                            <label for="profile-img-file-input" class="profile-photo-edit">
                                                <i class="ri-camera-fill"></i>
                                            </label>
                                        </div>
                                        <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                            name="logo" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger me-4" type="button"
                            onclick="back()">{{ __('translation.button.cancel') }}</button>
                        <button class="btn btn-primary" type="button"
                            onclick="updateSupplier()">{{ __('translation.button.submit') }}</button>
                    </div>
                </div>
            </form>
            @include('pages.supplier.log', ['logs' => $activities])
        </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const supplier = <?php echo json_encode($supplier); ?>;
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
            if (file && file.size > 5 * 1024 * 1024) {
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
        $('#imagePreview').on('click', function() {
            $('#profile-img-file-input').click();
        });
    </script>
    <script src="{{ asset('assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/supplier/supplier.edit.js') }}"></script>
@endSection()
