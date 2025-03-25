@extends('layouts/main')


@section('styles')
    <style>
        #sale_product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 60vh !important;
            overflow: auto !important;
        }

        #sale_product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
            border-collapse: separate !important;
        }

        .fiexd-header {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 4;
        }

        .fiexd-footer {
            position: sticky;
            bottom: 0;
            background-color: white;
            z-index: 4;
        }

        .step-container {
            position: relative;
            text-align: center;
            transform: translateY(-43%);
        }

        .checked {
            background-color: var(--primary-bg-color) !important;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #ebecf5;
            line-height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .process-bar {
            min-width: 40%;
            height: 30px;
            top: 14px;
            position: relative;
        }

        .status-number {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-width: 100px;
        }

        .progress {
            height: 2px;
            top: -10px;
            width: calc(100% - 100px);
            margin: auto;
        }

        @media only screen and (max-width: 696px) {
            .step-container {
                align-items: flex-start !important;
            }

            .progress {
                top: -18px;
            }
        }

        .ckbox input[type=checkbox][disabled]+span::after {
            background-color: rgba(138, 139, 141, 0.35) !important;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.saleOrder.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('sale-order') }}">{{ __('translation.saleOrder.saleOrder') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.create') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">{{ __('translation.saleOrder.create') }}</h3>
            <div class="process-bar">
                <div class="progress px-1">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="step-container d-flex justify-content-between">
                    <div class="status-number">
                        <div class="step-circle">01</div>
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.draft') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        <div class="step-circle">02</div>
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.confirmed') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        <div class="step-circle">03</div>
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.inTransit') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        <div class="step-circle">04</div>
                        <div class="status-number">
                            <span>{{ __('translation.saleOrder.delivered') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form id="sale_order_create" class="jquery-validate-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-4 col-lg-12">
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label for="supplier_name">{{ __('translation.saleOrder.customerName') }}<span
                                        class="text-danger">
                                        *</span></label>
                                <a class="add_new_item" href="/customer/create" target="_blank"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg><label>{{ __('translation.add_new') }}</label></a>
                            </div>
                            <div class="d-flex flex-column">
                                <input type="text" class="form-control" name="customer_name" id="customer_name" readonly
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <p id="client-address" class="text-muted"></p>
                            <p id="client-phone" class="text-muted"></p>
                            <p id="client-email" class="text-muted"></p>
                        </div>
                        <div class="form-group">
                            <label for="supplier_name">{{ __('translation.saleOrder.invoiceAddress') }}<span
                                    class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="invoice_address" id="invoice_address" disabled>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12">
                        <div class="form-group">
                            <label for="warehouse_name">{{ __('translation.saleOrder.shipFrom') }}<span
                                    class="text-danger">
                                    *</span></label>
                            <input type="text" class="form-control" name="warehouse_name" id="warehouse_name" readonly
                                required>
                            <input type="text" class="form-control" name="warehouse_id" id="warehouse_id" readonly
                                hidden>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label for="supplier_name">{{ __('translation.saleOrder.deliverAddress') }}<span
                                        class="text-danger">
                                        *</span></label>
                                <button id="addNewAddress" type="button" class="add_new_item"
                                    onclick="showModalUpdateAddress()"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg><label>{{ __('translation.change') }}</label></button>
                            </div>
                            <input type="text" class="form-control" name="deliver_address" id="deliver_address"
                                required disabled>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 form-group">
                        <div class="form-group">
                            <label for="supplier_name">{{ __('translation.saleOrder.deliverMethod') }}<span
                                    class="text-danger">
                                    *</span></label>
                            <select class="form-control select2-show-search form-select" name="deliver_method"
                                id="deliver_method" required>
                                @php
                                    $methodMap = [
                                        '1' => __('translation.saleOrder.standardShipping'),
                                        '2' => __('translation.saleOrder.inStorePickup'),
                                    ];
                                @endphp
                                <option></option>
                                @foreach (DeliverMethodEnum::getValues() as $methodValue)
                                    <option value="{{ $methodValue }}">{{ $methodMap[$methodValue] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="supplier_name">{{ __('translation.saleOrder.paymentTerm') }}<span
                                    class="text-danger">
                                    *</span></label>
                            <select class="form-control select2-show-search form-select" name="payment_term"
                                id="payment_term" required>
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
                                <option></option>
                                @foreach (PaymentTermTypeEnum::getValues() as $value)
                                    <option value="{{ $value }}">
                                        {{ __('translation.paymentTerm.' . $paymentTermOptions[$value]) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="tab-menu-heading border-bottom-0">
                    <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                        <nav id="navId" class="nav d-sm-flex d-block">
                            <a class="nav-link border border-bottom-lg-0 br-sm-5 active" data-bs-toggle="tab"
                                href="#tabProductList" id="productListLink">
                                {{ __('translation.saleOrder.list') }}
                            </a>
                            <a class="nav-link border border-bottom-lg-0 br-sm-5" data-bs-toggle="tab" href="#tabNote"
                                id="noteLink">
                                {{ __('translation.purchaseOrder.note') }}
                            </a>
                        </nav>
                        <button class="btn btn-primary mh-10" type="button"
                            onclick="selectProductModal()">{{ __('translation.add') }}</button>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabProductList">
                            <table class="table table-bordered w-100 text-nowrap border-bottom"
                                id="sale_product_datatable">
                                <thead class="fiexd-header">
                                    <tr>
                                        <th>ID</th>
                                        <th>{{ __('translation.product.image') }}</th>
                                        <th>{{ __('translation.product.name') }}</th>
                                        <th>{{ __('translation.product.unit') }}</th>
                                        <th>{{ __('translation.product.category') }}</th>
                                        <th>{{ __('translation.inventory.quantity') }}</th>
                                        <th>{{ __('translation.saleOrder.orderQuantity') }}</th>
                                        <th>{{ __('translation.saleOrder.unitPrice') }} (CAD)</th>
                                        <th>{{ __('translation.saleOrder.discount') }}</th>
                                        <th>{{ __('translation.saleOrder.totalPrice') }} (CAD)</th>
                                        <th>{{ __('translation.action') }}</th>
                                    </tr>
                                </thead>
                                <tfoot class="fiexd-footer">
                                    <tr></tr>
                                    <tr>
                                        <th colspan="9" class="fw-bold text-end">
                                            {{ __('translation.saleOrder.taxes') }}:
                                        </th>
                                        <th class="fw-bold text-end" id="taxList">

                                        </th>
                                        <th> </th>
                                    </tr>
                                    <tr>
                                        <th colspan="9" class="fw-bold text-end">
                                            {{ __('translation.saleOrder.totalAmount') }}:
                                        </th>
                                        <th id='totalAmount' class="fw-bold text-end">0.00$</th>
                                        <th> </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane" id="tabNote">
                            <textarea class="form-control" name="note" id="note" cols="30" rows="5"
                                placeholder="{{ __('translation.purchaseOrder.note') }} ..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger me-4" type="button"
                        onclick="cancelCreate()">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary  me-4" type="button" title="{{ __('message.createSaleOrder') }}"
                        onclick="confirmCreateSaleOrder()">{{ __('translation.button.create') }}</button>
                    <button class="btn btn-primary" type="button"
                        title="{{ __('message.sendAndCreatePurchaseOrder') }}"
                        onclick="confirmSendSaleOrder()">{{ __('translation.button.confirm') }}</button>
                </div>
            </form>
        </div>
        <div class="modal fade" id="update-address">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content country-select-modal">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('translation.saleOrder.addNewAddress') }}
                        </h5>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="jquery-validate-form" method="POST" id="createNewAddress">
                            <div class="row">
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="country">{{ __('translation.warehouse.country') }}<span
                                                class="text-danger">
                                                *</span></label>
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
                                        <select class="form-control select2-show-search form-select" name="country"
                                            id="country" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="country">{{ __('translation.warehouse.province') }}<span
                                                class="text-danger">
                                                *</span></label>
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
                                        <select class="form-control select2-show-search form-select" name="province"
                                            id="province" disabled required></select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="country">{{ __('translation.warehouse.city') }}<span
                                                class="text-danger">
                                                *</span></label>
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
                                        <select class="form-control select2-show-search form-select" name="city"
                                            id="city" disabled required></select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12 form-group">
                                    <label for="postal_code">{{ __('translation.customer.postalCode') }}</label>
                                    <input type="text" class="form-control" name="postal_code" id="postal_code">
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <label for="detail_address">{{ __('translation.warehouse.address') }}<span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" name="detail_address" id="detail_address"
                                        required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">edit
                        <button class="btn btn-danger"
                            data-bs-dismiss="modal">{{ __('translation.button.cancel') }}</button>
                        <button class="btn btn-primary" type="button"
                            onclick="createNewAddress()">{{ __('translation.button.confirm') }}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        const draftStatus = '{{ SaleOrderStatusEnum::DRAFT }}';
        const confirmStatus = '{{ SaleOrderStatusEnum::CONFIRM }}';
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/sale-order/sale-order.create.js') }}"></script>
@endSection()
