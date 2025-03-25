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
            <h1 class="page-title">{{ __('translation.customer.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('customer') }}">{{ __('translation.menu.customer') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.edit') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom">
            <h3 class="card-title">{{ __('translation.formTitle.customerEdit') }}</h3>
        </div>
        <div class="card-body">
            <form id="customer_update" class="jquery-validate-form" method="POST"
                action="{{ route('customer.update', $customer['id']) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="text" name="customerId" id="customerId" value="{{ $customer['id'] }}" hidden>
                <div class="row">
                    <div class="col-md-6 col-sm-12 row">
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="first_name">{{ __('translation.customer.firstName') }}<span class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="first_name" id="first_name"
                                value="{{ $customer['first_name'] }}">
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="last_name">{{ __('translation.customer.lastName') }}<span class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name"
                                value="{{ $customer['last_name'] }}">
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="phone_number">{{ __('translation.customer.phoneNumber') }}<span
                                    class="text-danger"> *</span></label>
                            <input type="text" class="form-control phone" name="phone_number" id="phone_number" required
                                placeholder="(XXX)XXXX-XXX">
                            <input type="text" class="form-control phone" name="phone" id="phone" hidden
                                value={{ $customer['phone'] }}>
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="email">{{ __('translation.customer.email') }}<span class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="email" id="email" required
                                value="{{ $customer['email'] }}">
                        </div>
                        <div class="col-xl-6 col-lg-12 form-group">
                            <div class="d-flex justify-content-between">
                                <label for="country">{{ __('translation.customer.country') }}<span class="text-danger">
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
                                <label for="country">{{ __('translation.customer.province') }}<span class="text-danger">
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
                                <label for="country">{{ __('translation.customer.city') }}<span class="text-danger">
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
                        <div class="col-xl-6 col-lg-12 form-group">
                            <label for="postal_code">{{ __('translation.customer.postalCode') }}</label>
                            <input type="text" class="form-control" name="postal_code" id="postal_code"
                                value="{{ $customer['postal_code'] }}">
                        </div>
                        <div class=" col-xl-6 col-lg-12 form-group">
                            <label for="detail_address">{{ __('translation.customer.address') }}<span
                                    class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="detail_address" id="detail_address"
                                value="{{ $customer['detail_address'] }}">
                        </div>
                        <div class=" col-xl-6 col-lg-12 form-group">
                            <label for="contact_url">{{ __('translation.customer.contactURL') }}</label>

                            <input type="text" class="form-control" name="contact_url" id="contact_url"
                                value="{{ $customer['contact_url'] }}">
                        </div>
                        <div class=" col-xl-6 col-lg-12 form-group">
                            <label for="discount">{{ __('translation.customer.discount') }}<span>
                                    %</span></label>
                            <input type="number" class="form-control" min="0" max="100" name="discount"
                                id="discount" value="{{ $customer['discount'] }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 row">
                        <label for="name">{{ __('translation.customer.customerImage') }}</label>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-12 col-md-12">
                                    <div class="d-flex flex-wrap align-items-center justify-content-center flex-column">
                                        <div class="profile-img-main" style="margin-top: -70px;">
                                            <img src="{{ $customer->avatar_url ?? URL::asset('assets/images/add-image.png') }}"
                                                id="imagePreview" alt="img" class="m-0 p-1 border select-image">
                                            <label for="profile-img-file-input" class="profile-photo-edit">
                                                <i class="ri-camera-fill"></i>
                                            </label>
                                        </div>
                                        <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                            name="avatar" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-menu-heading border-bottom-0">
                    <div class="tabs-menu4 border-bottom-sm d-flex justify-content-between align-items-end">
                        <nav id="navId" class="nav d-sm-flex d-block">
                            <a class="nav-link border border-bottom-0 br-sm-5 active" data-bs-toggle="tab"
                                href="#tabCompanyInformation">
                                {{ __('translation.company.information') }}
                            </a>
                            <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab"
                                href="#tabFinancialInformation">
                                {{ __('translation.customer.financial_information') }}
                            </a>
                        </nav>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabCompanyInformation">
                            <div class="row">
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <label for="company_name">{{ __('translation.company.name') }}<span
                                            class="text-danger">
                                        </span></label>
                                    <input type="text" class="form-control" name="company_name" id="company_name"
                                        value="{{ $customer['company_name'] }}">
                                </div>
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <label for="company_phone_number">{{ __('translation.company.phone') }}</label>
                                    <input type="text" class="form-control phone" name="company_phone_number"
                                        id="company_phone_number" placeholder="(XXX)XXXX-XXX">
                                    <input type="text" class="form-control phone" name="company_phone"
                                        id="company_phone" hidden value={{ $customer['company_phone'] }}>
                                </div>
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <label for="company_email">{{ __('translation.company.email') }}<span
                                            class="text-danger">
                                        </span></label>
                                    <input type="text" class="form-control" name="company_email" id="company_email"
                                        value="{{ $customer['company_email'] }}">
                                </div>
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <label for="company_site">{{ __('translation.company.website') }}</label>
                                    <input type="text" class="form-control" name="company_site" id="company_site"
                                        value="{{ $customer['company_site'] }}">
                                </div>
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="company_country">{{ __('translation.company.country') }}</label>
                                        <button type="button" class="add_new_item"
                                            onclick="showModalCreate('country')"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="16">
                                                </line>
                                                <line x1="8" y1="12" x2="16" y2="12">
                                                </line>
                                            </svg><label>{{ __('translation.add_new') }}</label></button>
                                    </div>
                                    <div class="d-flex flex-column-reverse">
                                        <select class="form-control select2-show-search form-select"
                                            name="company_country" id="company_country"> </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="country">{{ __('translation.customer.province') }}</label>
                                        <button type="button" class="add_new_item"
                                            onclick="showModalCreate('province')"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="16">
                                                </line>
                                                <line x1="8" y1="12" x2="16" y2="12">
                                                </line>
                                            </svg><label>{{ __('translation.add_new') }}</label></button>
                                    </div>
                                    <div class="d-flex flex-column-reverse">
                                        <select class="form-control select2-show-search form-select"
                                            name="company_province" id="company_province"></select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="company_city">{{ __('translation.company.city') }}</label>
                                        <button type="button" class="add_new_item"
                                            onclick="showModalCreate('city')"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="16">
                                                </line>
                                                <line x1="8" y1="12" x2="16" y2="12">
                                                </line>
                                            </svg><label>{{ __('translation.add_new') }}</label></button>
                                    </div>
                                    <div class="d-flex flex-column-reverse">
                                        <select class="form-control select2-show-search form-select" name="company_city"
                                            id="company_city"></select>
                                    </div>
                                </div>
                                <div class=" col-xl-4 col-lg-12 form-group">
                                    <label for="company_postal_code">{{ __('translation.company.costPostal') }}</label>

                                    <input type="text" class="form-control" name="company_postal_code"
                                        id="company_postal_code" value="{{ $customer['company_postal_code'] }}">
                                </div>
                                <div class=" col-xl-4 col-lg-12 form-group">
                                    <label for="company_address">{{ __('translation.company.detail_address') }}</label>
                                    <input type="text" class="form-control" name="company_address"
                                        id="company_address" value="{{ $customer['company_address'] }}">
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="tabFinancialInformation">
                            <div class="row">
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="payment_method">{{ __('translation.customer.payment_method') }}
                                        </label>
                                    </div>
                                    <div class="d-flex flex-column-reverse">
                                        <select class="form-control select2-show-search form-select" name="payment_method"
                                            id="payment_method">
                                            @foreach (\App\Enums\PaymentTypeEnum::getValues() as $value)
                                                <option value="{{ $value }}"
                                                    {{ $customer['payment_method'] == $value ? 'selected' : '' }}>
                                                    {{ __('translation.payment.' . $value) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="payment_terms">{{ __('translation.customer.payment_terms') }} </label>
                                    </div>
                                    <div class="d-flex flex-column-reverse">
                                        <select class="form-control select2-show-search form-select" name="payment_term"
                                            id="payment_term">
                                            @php
                                                $paymentTermOptions = [
                                                    1 => 'immediate',
                                                    2 => '15_days',
                                                    3 => '20_days',
                                                    4 => '30_days',
                                                    5 => '45_days',
                                                    6 => 'end_month',
                                                ];
                                            @endphp
                                            @foreach (\App\Enums\PaymentTermTypeEnum::getValues() as $value)
                                                <option value="{{ $value }}"
                                                    {{ $customer['payment_term'] == $value ? 'selected' : '' }}>
                                                    {{ __('translation.paymentTerm.' . $paymentTermOptions[$value]) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger me-4" type="button"
                        onclick="back()">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary" type="button"
                        onclick="updateCustomer()">{{ __('translation.button.submit') }}</button>
                </div>
            </form>
            @include('pages.customer.log', ['logs' => $activities])
        </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const customer = <?php echo json_encode($customer); ?>;
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
        $('#imagePreview').on('click', function() {
            $('#profile-img-file-input').click();
        });
    </script>
    <script src="{{ asset('assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/customer/customer.edit.js') }}"></script>
@endSection()
