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
        <h1 class="page-title">{{ __('translation.customer.management') }}</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('customer') }}">{{ __('translation.menu.customer') }}</a></li>
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
                <h3 class="card-title">{{ __('translation.customer.detail') }}</h3>
            </div>
            <div class="card-body">
                <div class="pt-2 mb-4 m-0" style="border: 1px solid #eaedf1">
                    <label class="ps-4 fw-bold">{{ __('translation.customer.information') }}</label>
                    <div class="d-flex p-1">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="first_name">{{ __('translation.customer.firstName') }}</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ $customer['first_name'] }}" disabled>
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="last_name">{{ __('translation.customer.lastName') }}</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{ $customer['last_name'] }}" disabled>
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="phone_number">{{ __('translation.customer.phoneNumber') }}</label>
                                    <input type="text" class="form-control phone" name="phone_number" id="phone_number" disabled placeholder="(XXX)XXXX-XXX">
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="email">{{ __('translation.customer.email') }}</label>
                                    <input type="text" class="form-control" name="email" id="email" disabled value="{{ $customer['email'] }}">
                                </div>
                                <div class=" col-xl-12 col-lg-12 form-group">
                                    <label for="detail_address">{{ __('translation.customer.address') }}</label>
                                    <input type="text" class="form-control" name="detail_address" id="detail_address" value="{{ $customer['address'] }}" disabled>
                                </div>
                                <div class=" col-xl-6 col-lg-12 form-group">
                                    <label for="contact_url">{{ __('translation.customer.contactURL') }}</label>
                                    <input type="text" class="form-control" name="contact_url" id="contact_url" value="{{ $customer['contact_url'] }}" disabled>
                                </div>
                                <div class=" col-xl-6 col-lg-12 form-group">
                                    <label for="discount">{{ __('translation.customer.discount') }}</label>
                                    <input type="text" class="form-control" name="discount" id="discount" value="{{ $customer['discount'] }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="name">{{ __('translation.customer.customerImage') }}</label>
                            <div class="d-flex flex-wrap align-items-center justify-content-center flex-column">
                                <div class="profile-img-main">
                                    <img src="{{ $customer->avatar_url ?? URL::asset('assets/images/no-image.png') }}" id="imagePreview" alt="img" class="m-0 p-1 border select-image img-overlay-light-box">
                                </div>
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input" name="avatar" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="tab-menu-heading border-bottom-0">
                        <div class="tabs-menu4 border-bottom-sm d-flex justify-content-between align-items-end">
                            <nav id="navId" class="nav d-sm-flex d-block">
                                <a class="nav-link border border-bottom-0 br-sm-5 active" data-bs-toggle="tab" href="#tabCompanyInformation">
                                    {{ __('translation.company.information') }}
                                </a>
                                <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab" href="#tabFinancialInformation">
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
                                        <label for="company_name">{{ __('translation.company.name') }}<span class="text-danger">
                                            </span></label>
                                        <input type="text" class="form-control" name="company_name" id="company_name" value="{{ $customer['company_name'] }}" disabled>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 form-group">
                                        <label for="company_phone_number">{{ __('translation.company.phone') }}</label>
                                        <input type="text" class="form-control phone" name="company_phone_number" id="company_phone_number" disabled placeholder="(XXX)XXXX-XXX">
                                    </div>
                                    <div class="col-xl-4 col-lg-12 form-group">
                                        <label for="company_email">{{ __('translation.company.email') }}<span class="text-danger">
                                            </span></label>
                                        <input type="text" class="form-control" name="company_email" id="company_email" value="{{ $customer['company_email'] }}" disabled>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 form-group">
                                        <label for="company_site">{{ __('translation.company.website') }}</label>
                                        <input type="text" class="form-control" name="company_site" id="company_site" value="{{ $customer['company_site'] }}" disabled>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 form-group">
                                        <label for="company_country">{{ __('translation.company.country') }}</label>
                                        <input type="text" class="form-control" name="company_country" id="company_country" value="{{ $customer['company_country'] }}" disabled>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 form-group">
                                        <label for="company_province">{{ __('translation.company.province') }}</label>
                                        <input type="text" class="form-control" name="company_province" id="company_province" value="{{ $customer['company_province'] }}" disabled>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 form-group">
                                        <label for="company_city">{{ __('translation.company.city') }}</label>
                                        <input type="text" class="form-control" name="company_city" id="company_city" value="{{ $customer['company_city'] }}" disabled>
                                    </div>
                                    <div class=" col-xl-4 col-lg-12 form-group">
                                        <label for="company_postal_code">{{ __('translation.company.costPostal') }}</label>

                                        <input type="text" class="form-control" name="company_postal_code" id="company_postal_code" value="{{ $customer['company_postal_code'] }}" disabled>
                                    </div>
                                    <div class=" col-xl-4 col-lg-12 form-group">
                                        <label for="company_address">{{ __('translation.company.detail_address') }}</label>
                                        <input type="text" class="form-control" name="company_address" id="company_address" value="{{ $customer['company_address'] }}" disabled>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="tabFinancialInformation">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-12 form-group">
                                        <div class="d-flex justify-content-between">
                                            <label for="payment_method">{{ __('translation.customer.payment_method') }} </label>
                                        </div>
                                        <div class="d-flex flex-column-reverse">
                                            <select class="form-control select2-show-search form-select" name="payment_method" id="payment_method" disabled>
                                                @foreach(\App\Enums\PaymentTypeEnum::getValues() as $value)
                                                <option value="{{ $value }}" {{ $customer['payment_method'] == $value ? 'selected' : '' }}>
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
                                            <select class="form-control select2-show-search form-select" name="payment_term" id="payment_term" disabled>
                                                @php
                                                    $paymentTermOptions = [
                                                        1 => 'immediate',
                                                        2 => '15_days',
                                                        3 => '20_days',
                                                        4 => '30_days',
                                                        5 => '45_days',
                                                        6 => 'end_month'
                                                    ];
                                                @endphp
                                                @foreach(\App\Enums\PaymentTermTypeEnum::getValues() as $value)
                                                <option value="{{ $value }}" {{ $customer['payment_term'] == $value ? 'selected' : '' }}>
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
                </div>

                @include('pages.customer.log', ['logs' => $activities])
            </div>
        </div>
    </div>
</div>
<!-- END ROW -->
@endSection()

@section('scripts')
<script>
    const customer = <?php echo json_encode($customer); ?>;
</script>
<script src="{{ asset('assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('assets/js/page/customer/customer.detail.js') }}"></script>
@endSection()