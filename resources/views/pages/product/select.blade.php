<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered text-nowrap border-bottom w-100" id="product-select">
            <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="text-filter">#</th>
                    <th class="no-sort">{{ __('translation.product.image') }}</th>
                    <th class="text-filter">{{ __('translation.product.name') }}</th>
                    <th class="text-filter">{{ __('translation.product.category') }}</th>
                    <th class="text-filter">{{ __('translation.product.unitPrice') }}</th>
                    <th class="text-filter">{{ __('translation.product.unit') }}</th>
                    <th>{{ __('translation.product.quantity') }}</th>
                    <th class="text-filter">{{ __('translation.product.maxQuantity') }}</th>
                    <th class="text-filter">{{ __('translation.product.minQuantity') }}</th>
                </tr>
            </thead>
        </table>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="selectProduct()">{{ __('translation.button.confirm') }}</button>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/page/product/product.select.js?v=1') }}"></script>
