<style>
    .select2-container {
        width: 100% !important;
    }

    .modal-body>.card-body>.table-responsive>.dataTables_wrapper>.row:nth-of-type(2)>.col-sm-12 {
        max-height: 50vh !important;
    }
</style>
<div class="pe-4 ps-4 row">
    <div class="col-xl-6 col-lg-12">
        <div id="warehouseSelect" style="display: none;">
            <label for="warehouse_id">{{ __('translation.shelve.warehouse') }}</label>
            <div class="d-flex flex-column-reverse">
                <select class="form-control select2-show-search form-select" name="warehouse_id"
                    id="warehouse_id"></select>
            </div>
        </div>
    </div>
    @if (PermissionRole::checkPermission([Permission::SHELVE]))
        <div class="col-xl-6 col-lg-12 d-flex justify-content-end align-items-end">
            <button class="btn btn-primary d-flex" type="button"
                onclick="showModalCreate()">{{ __('translation.shelve.create') }}</button>
        </div>
    @endif
</div>


<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered text-nowrap border-bottom w-100" id="shelve-datatable">
            <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="text-filter">#</th>
                    <th class="text-filter">{{ __('translation.shelve.name') }}</th>
                    <th class="text-filter">{{ __('translation.shelve.warehouse') }}</th>
                    <th class="text-filter">{{ __('translation.shelve.location') }}</th>
                    <th class="text-filter">{{ __('translation.shelve.productQuantity') }}</th>
                    <th class="text-filter">{{ __('translation.warehouse.lastUpdatedDate') }}</th>
                </tr>
            </thead>
        </table>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="selectShelve()">{{ __('translation.button.confirm') }}</button>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/page/shelve/shelve.select.js') }}"></script>
