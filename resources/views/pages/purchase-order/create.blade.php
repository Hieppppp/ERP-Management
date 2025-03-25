@extends('layouts/main')


@section('styles')
    <style>
        #product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 {
            max-height: 40vh !important;
            overflow: auto !important;
        }

        #product_datatable_wrapper>.row:nth-of-type(2)>.col-sm-12 table {
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
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.purchaseOrder.management') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ url('purchase-order') }}">{{ __('translation.purchaseOrder.purchaseOrder') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.create') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between">
            <h3 class="card-title">{{ __('translation.purchaseOrder.create') }}</h3>
            <div class="process-bar">
                <div class="progress px-1">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="step-container d-flex justify-content-between">
                    <div class="status-number">
                        <div class="step-circle">01</div>
                        <div class="status-number">
                            <span>{{ __('translation.purchaseOrder.draft') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        <div class="step-circle">02</div>
                        <div class="status-number">
                            <span>{{ __('translation.purchaseOrder.pending') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        <div class="step-circle">03</div>
                        <div class="status-number">
                            <span>{{ __('translation.purchaseOrder.pendingShelve') }}</span>
                        </div>
                    </div>
                    <div class="status-number">
                        <div class="step-circle">04</div>
                        <div class="status-number">
                            <span>{{ __('translation.purchaseOrder.done') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-body">
            <form id="purchase_order_create" class="jquery-validate-form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="supplier_name">{{ __('translation.purchaseOrder.supplierName') }}<span
                                class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" name="supplier_name" id="supplier_name" readonly
                            value="{{ $supplier ? $supplier->name : '' }}" required>
                        <input type="text" class="form-control" name="supplier_id" id="supplier_id" readonly
                            value="{{ $supplier ? $supplier->id : '' }}" hidden>
                    </div>
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="scheduled_date">{{ __('translation.purchaseOrder.scheduleDate') }}</label>
                        <div class="input-group">
                            <div class="input-group-text bg-primary-transparent text-primary">
                                <i class="fe fe-calendar text-20"></i>
                            </div>
                            <input class="form-control fc-datepicker" readonly data-date-format="yyyy-mm-dd"
                                name="scheduled_date" id="scheduled_date" placeholder="YYYY-MM-DD" type="text">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 form-group">
                        <label for="warehouse_name">{{ __('translation.purchaseOrder.shipTo') }}<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" name="warehouse_name" id="warehouse_name" readonly
                            required>
                        <input type="text" class="form-control" name="warehouse_id" id="warehouse_id" readonly hidden>
                        <div class="form-group mt-2">
                            <p id="warehouse-address" class="text-muted"></p>
                            <p id="warehouse-contact" class="text-muted"></p>
                        </div>
                    </div>
                </div>
                <div class="tab-menu-heading border-bottom-0">
                    <div class="tabs-menu4 border-bottomo-sm d-flex justify-content-between align-items-end">
                        <nav id="navId" class="nav d-sm-flex d-block">
                            <p class="card-title">
                                {{ __('translation.purchaseOrder.listOrderProduct') }}
                            </p>
                        </nav>
                        <button class="btn btn-primary mh-10" type="button"
                            onclick="selectModal()">{{ __('translation.add') }}</button>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body mb-5">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabParentProduct">
                            <table class="table table-bordered w-100 text-nowrap border-bottom" id="product_datatable">
                                <thead class="fiexd-header">
                                    <tr>
                                        <th class="text-filter">#</th>
                                        <th class="no-sort">{{ __('translation.product.image') }}</th>
                                        <th class="text-filter">{{ __('translation.product.name') }}</th>
                                        <th class="text-filter">{{ __('translation.product.unit') }}</th>
                                        <th class="text-filter">{{ __('translation.product.category') }}</th>
                                        <th class="text-filter">{{ __('translation.product.quantity') }}</th>
                                        <th class="text-filter">{{ __('translation.purchaseOrder.unitCost') }} (CAD)</th>
                                        <th class="text-filter">{{ __('translation.purchaseOrder.totalCost') }} (CAD)</th>
                                        <th class="no-sort">{{ __('translation.action') }}</th>
                                    </tr>
                                </thead>
                                <tfoot class="fiexd-footer">
                                    <tr>
                                        <th colspan="7" class="fw-bold">{{ __('translation.purchaseOrder.total') }}:
                                        </th>
                                        <th id='totalAmount' class="fw-bold">0.00$</th>
                                        <th> </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger me-4" type="button"
                        onclick="back()">{{ __('translation.button.cancel') }}</button>
                    <button class="btn btn-primary  me-4" type="button" title="{{ __('message.createPurchaseOrder') }}"
                        onclick="confirmCreatePurchaseOrder()">{{ __('translation.button.create') }}</button>
                    <button class="btn btn-primary" type="button"
                        title="{{ __('message.sendAndCreatePurchaseOrder') }}"
                        onclick="confirmSendPurchaseOrder()">{{ __('translation.purchaseOrder.sendOrder') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script>
        let supplier = @json($supplier);
        let product = @json($product)
    </script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/purchase-order/purchase-order.create.js') }}"></script>
@endSection()
