<div class="card-body">
    <div class="table-responsive">
        <table class="table w-100 table-bordered text-nowrap border-bottom" id="inventory-select-datatable">
            <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="text-filter">{{ __('translation.inventory.batchId') }}</th>
                    <th class="text-filter">{{ __('translation.batch.receivedDate') }}</th>
                    <th class="text-filter">{{ __('translation.shelve.shelve') }}</th>
                    <th class="text-filter">{{ __('translation.shelve.warehouse') }}</th>
                    <th class="text-filter no-sort">{{ __('translation.shelve.location') }}</th>
                    <th class="text-filter">{{ __('translation.inventory.stockQuantity') }}</th>
                </tr>
            </thead>
        </table>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="selectInventory()">{{ __('translation.button.confirm') }}</button>
        </div>
    </div>
</div>
<script>
    var includeProductId = '{{ $productId }}';
    var includeWarehouseId = '{{ $warehouseId }}';
</script>
<script src="{{ asset('assets/js/page/inventory/inventory.select.js?v=1') }}"></script>
