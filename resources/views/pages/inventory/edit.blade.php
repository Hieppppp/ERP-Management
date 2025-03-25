<div class="row">
    <div class="col-md-6 form-group">
        <label>{{ __('translation.shelve.id') }}</label>
        <input type="text" class="form-control" value="{{ $inventory['shelve']['code'] }}" disabled>
    </div>
    <div class="col-md-6 form-group">
        <label>{{ __('translation.shelve.warehouse') }}</label>
        <input type="text" class="form-control" value="{{ $inventory['shelve']['warehouses']['code'] }}" disabled>
    </div>
    <div class="col-md-6 form-group">
        <label>{{ __('translation.shelve.location') }}</label>
        <input type="text" class="form-control" value="{{ $inventory['shelve']['location'] }}" disabled>
    </div>
</div>
<hr>
<form id="inventory_edit" class="jquery-validate-form" method="POST"
    action="{{ route('inventory.update', $inventory['id']) }}">
    @csrf
    @method('PUT')
    <input type="number" id="inventory_id" name="inventory_id" value="{{ $inventory['id'] }}" hidden>
    <div class="row">
        <div class="col-md-6 form-group position-relative">
            <label for="current_quantity">{{ __('translation.inventory.stockQuantity') }}</label>
            <input type="number" class="form-control" id="current_quantity" name="current_quantity"
                value="{{ $inventory['quantity'] }}" readonly required>
        </div>
        <div class="col-md-6 form-group">
            <label for="new_quantity">{{ __('translation.inventory.newStockQuantity') }} <span
                    class="text-danger">*</span></label>
            <input type="number" class="form-control" id="new_quantity" min="0" name="new_quantity" required>
        </div>
        <div class="col-md-12 form-group">
            <label for="current_quantity">{{ __('translation.inventory.note') }}</label>
            <div class="ql-wrapper ql-wrapper-modal">
                <div class="flex-1" id="note"></div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-danger" type="button"
            data-bs-dismiss="modal">{{ __('translation.button.cancel') }}</button>
        <button class="btn btn-primary" type="button"
            onclick="confirmEditInventory()">{{ __('translation.button.submit') }}</button>
    </div>
</form>
<script>
    note = initQuillEditor('#note');
</script>
