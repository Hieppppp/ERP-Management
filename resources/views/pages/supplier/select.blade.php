<div class="card-body">
    <div class="table-responsive">
        <table class="table w-100 table-bordered text-nowrap border-bottom" id="supplier-select-datatable">
            <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="text-filter">#</th>
                    <th class="no-sort">{{ __('translation.supplier.logo') }}</th>
                    <th class="text-filter">{{ __('translation.supplier.name') }}</th>
                    <th class="text-filter">{{ __('translation.supplier.email') }}</th>
                    <th class="text-filter">{{ __('translation.supplier.phoneNumber') }}</th>
                    <th class="text-filter">{{ __('translation.supplier.address') }}</th>
                    <th>{{ __('translation.supplier.productSupplied') }}</th>
                </tr>
            </thead>
        </table>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="selectSupplier()">{{ __('translation.button.confirm') }}</button>
        </div>
    </div>
</div>
<script>
    var includeProductId = '{{ $productId }}';
</script>
<script src="{{ asset('assets/js/page/supplier/supplier.select.js') }}"></script>
