<div class="card-body">
    <div class="table-responsive">
        <table class="table w-100 table-bordered text-nowrap border-bottom" id="select-customer-datatable">
            <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th class="text-filter">ID</th>
                    <th class="no-sort">{{ __('translation.customer.avatar') }}</th>
                    <th class="text-filter">{{ __('translation.customer.name') }}</th>
                    <th class="text-filter">{{ __('translation.customer.email') }}</th>
                    <th class="text-filter">{{ __('translation.customer.phoneNumber') }}</th>
                    <th class="text-filter">{{ __('translation.customer.address') }}</th>
                    <th class="text-filter">{{ __('translation.customer.create_at') }}</th>
                </tr>
            </thead>
        </table>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="selectCustomer()">{{ __('translation.button.confirm') }}</button>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/page/customer/customer.select.js?v=1') }}"></script>
