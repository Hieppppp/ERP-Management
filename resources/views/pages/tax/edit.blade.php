<form id="tax_update" class="jquery-validate-form" method="POST" action="{{ route('tax.edit', $tax['id']) }}">
    @csrf
    <input type="text" name="id" id="taxId" hidden value="{{ $tax['id'] }}">
    <div class="row">
        <div class="col-xl-6 form-group">
            <label for="name">{{ __('translation.tax.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $tax['name'] }}">
        </div>
        <div class="col-xl-6 form-group">
            <label for="code">{{ __('translation.tax.code') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="code" id="code" value="{{ $tax['code'] }}">
        </div>
        <div class=" col-xl-6 form-group">
            <label for="rate">{{ __('translation.tax.rate') }} (%)<span class="text-danger"> *</span></label>
            <input type="number" class="form-control" name="rate" id="rate" value="{{ $tax['rate'] }}">
        </div>
        <div class=" col-xl-6 form-group">
            <label for="description">{{ __('translation.tax.description') }}</label>
            <input type="text" class="form-control" name="description" id="description"
                value="{{ $tax['description'] }}">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="editTax()">{{ __('translation.button.submit') }}</button>
        </div>
    </div>
</form>
</div>
</div>
<script>
    if (typeof tax === 'undefined') {
        let tax = null;
    }
    tax = <?php echo json_encode($tax); ?>;
</script>
<script src="{{ asset('assets/js/page/tax/tax.edit.js') }}"></script>
