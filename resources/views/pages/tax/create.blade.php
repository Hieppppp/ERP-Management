<form id="tax_create" class="jquery-validate-form" method="POST" action="{{ url('tax') }}">
    @csrf
    <div class="row">
        <div class="col-xl-6 form-group">
            <label for="name">{{ __('translation.tax.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="col-xl-6 form-group">
            <label for="code">{{ __('translation.tax.code') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="code" id="code">
        </div>
        <div class=" col-xl-6 form-group">
            <label for="rate">{{ __('translation.tax.rate') }} (%)<span class="text-danger"> *</span></label>
            <input type="number" class="form-control" name="rate" id="rate">
        </div>
        <div class=" col-xl-6 form-group">
            <label for="description">{{ __('translation.tax.description') }}</label>
            <input type="text" class="form-control" name="description" id="description">
        </div>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="createTax()">{{ __('translation.button.create') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/tax/tax.create.js') }}"></script>
