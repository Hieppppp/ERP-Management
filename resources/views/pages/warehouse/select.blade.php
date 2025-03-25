<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered text-nowrap border-bottom w-100" id="warehouse-datatable">
            <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="text-filter">#</th>
                    <th class="text-filter">{{ __('translation.warehouse.name') }}</th>
                    <th class="text-filter">{{ __('translation.warehouse.address') }}</th>
                    <th>{{ __('translation.warehouse.numberOfShelves') }}</th>
                    <th class="text-filter">{{ __('translation.warehouse.lastUpdatedDate') }}</th>
                </tr>
            </thead>
        </table>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="selectWarehouse()">{{ __('translation.button.confirm') }}</button>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/page/warehouse/warehouse.select.js') }}"></script>
