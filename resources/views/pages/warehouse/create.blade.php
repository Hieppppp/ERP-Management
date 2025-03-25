<form id="warehouse_create" class="jquery-validate-form" method="POST" action="{{ url('warehouse') }}">
    @csrf
    <div class="row">
        <div class="col-md-12 col-sm-12 row">
            <div class="col-xl-6 col-lg-12 form-group">
                <label for="name">{{ __('translation.warehouse.name') }}<span class="text-danger">
                        *</span></label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <label for="postal_code">{{ __('translation.warehouse.postalCode') }}<span class="text-danger">
                        *</span></label>
                <input type="text" class="form-control" name="postal_code" id="postal_code">
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <div class="d-flex justify-content-between">
                    <label for="country">{{ __('translation.warehouse.country') }}<span class="text-danger">
                            *</span></label>
                    <button type="button" class="add_new_item" onclick="showModalCreate('country')"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg><label>{{ __('translation.add_new') }}</label></button>
                </div>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="country" id="country" required>
                    </select>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <div class="d-flex justify-content-between">
                    <label for="country">{{ __('translation.warehouse.province') }}<span class="text-danger">
                            *</span></label>
                    <button type="button" class="add_new_item" onclick="showModalCreate('province')"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg><label>{{ __('translation.add_new') }}</label></button>
                </div>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="province" id="province"
                        disabled></select>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <div class="d-flex justify-content-between">
                    <label for="country">{{ __('translation.warehouse.city') }}<span class="text-danger">
                            *</span></label>
                    <button type="button" class="add_new_item" onclick="showModalCreate('city')"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-plus-circle plus-down-add">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg><label>{{ __('translation.add_new') }}</label></button>
                </div>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="city" id="city"
                        disabled></select>
                </div>
            </div>
            <div class=" col-xl-6 col-lg-12 form-group">
                <label for="detail_address">{{ __('translation.warehouse.address') }}<span class="text-danger">
                        *</span></label>
                <input type="text" class="form-control" name="detail_address" id="detail_address">
            </div>
            <div class="col-xl-6 col-lg-12 form-group d-flex flex-column">
                <label for="phone_number">{{ __('translation.warehouse.contact') }}<span class="text-danger">
                        *</span></label>
                <input type="text" class="form-control phone" name="phone_number" id="phone_number" required
                    placeholder="(XXX)XXXX-XXX">
                <input type="text" class="form-control phone" name="contact" id="contact" hidden>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="warehouseCreate()">{{ __('translation.button.create') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/warehouse/warehouse.create.js') }}"></script>
