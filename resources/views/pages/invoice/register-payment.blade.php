<form id="registerPayment" class="jquery-validate-form" method="POST">
    @csrf
    <div class="row">
        <div class="col-xl-6 form-group">
            <div class="col-xl-12 form-group">
                <label for="payment_type">{{ __('translation.registerPayment.paymentMethod') }}<span class="text-danger">
                        *</span></label>
                <select class="form-control select2-show-search form-select" name="payment_type" id="payment_type"
                    required>
                    @foreach (PaymentTypeEnum::getValues() as $value)
                        <option value="{{ $value }}"
                            {{ $saleOrder?->customer?->payment_method ? ($saleOrder?->customer?->payment_method == $value ? 'selected' : '') : ($value == 'check' ? 'selected' : '') }}>
                            {{ __('translation.payment.' . $value) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-12 form-group">
                <label for="date">{{ __('translation.registerPayment.paymentDate') }}<span class="text-danger">
                        *</span></label>
                <div class="input-group">
                    <div class="input-group-text bg-primary-transparent text-primary">
                        <i class="fe fe-calendar text-20"></i>
                    </div>
                    <input class="form-control fc-datepicker" readonly data-date-format="yyyy-mm-dd" name="date"
                        id="date" placeholder="YYYY-MM-DD" type="text" required value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
        <div class="col-xl-6 form-group">
            <div class="col-xl-12 form-group">
                <label for="paid_amount">{{ __('translation.registerPayment.amountPaid') }} (CAD)<span
                        class="text-danger">
                        *</span></label>
                <input type="number" class="form-control" name="paid_amount" id="paid_amount" style="height: 38px;"
                    required value="{{ $totalAmountUnpaid }}" max="{{ $totalAmountUnpaid }}" min ="0">
            </div>
            <div class="col-xl-12 form-group d-flex flex-column">
                <label for="customerInvoice">{{ __('translation.registerPayment.customerInvoice') }}</label>
                <a href="/sale-order/{{ $saleOrder->id }}" target="_blank">
                    {{ $saleOrder->code }}
                </a>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-danger me-4" data-bs-dismiss="modal"
                type="button">{{ __('translation.button.cancel') }}</button>
            <button class="btn btn-primary" type="button"
                onclick="confirm()">{{ __('translation.button.confirm') }}</button>
        </div>
    </div>
</form>

<script src="{{ asset('assets/js/page/invoice/register-payment.js') }}"></script>
