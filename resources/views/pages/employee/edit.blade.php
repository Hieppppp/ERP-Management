<form id="employee_update" class="jquery-validate-form" method="POST"
    action="{{ route('employee.update', $employee['id']) }}">
    @csrf
    @method('PUT')
    <input type="text" name="" id="employeeId" value="{{ $employee['id'] }}" hidden>
    <div class="row">
        <div class="col-md-12 col-sm-12 row">
            <div class="col-xl-6 col-lg-12 form-group">
                <label for="name">{{ __('translation.employee.name') }}<span class="text-danger">
                        *</span></label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $employee['name'] }}">
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <label for="email">{{ __('translation.employee.email') }}<span class="text-danger">
                        *</span></label>
                <input type="text" class="form-control" name="email" id="email" value="{{ $employee['email'] }}">
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <div class="d-flex justify-content-between">
                    <label for="department_id">{{ __('translation.employee.department') }}<span class="text-danger">
                            *</span></label>
                </div>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="department_id" id="department_id" required>
                    </select>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <div class="d-flex justify-content-between">
                    <label for="position_id">{{ __('translation.employee.position') }}<span class="text-danger">
                            *</span></label>
                </div>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="position_id"
                        id="position_id" required> </select>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <div class="d-flex justify-content-between">
                    <label for="country">{{ __('translation.warehouse.country') }}</label>
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
                    <label for="country">{{ __('translation.warehouse.province') }}</label>
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
                    <select class="form-control select2-show-search form-select" name="province"
                        id="province"></select>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <div class="d-flex justify-content-between">
                    <label for="country">{{ __('translation.warehouse.city') }}</label>
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
                    <select class="form-control select2-show-search form-select" name="city"
                        id="city"></select>
                </div>
            </div>
            <div class=" col-xl-6 col-lg-12 form-group">
                <label for="postal_code">{{ __('translation.employee.postalCode') }}</label>
                <input type="text" class="form-control" name="postal_code" id="postal_code" value="{{ $employee['postal_code'] }}">
            </div>
            <div class=" col-xl-6 col-lg-12 form-group">
                <label for="detail_address">{{ __('translation.employee.address') }}</label>
                <input type="text" class="form-control" name="detail_address" id="detail_address" value="{{ $employee['detail_address'] }}">
            </div>
            <div class="col-xl-6 col-lg-12 form-group d-flex flex-column">
                <label for="phone_number">{{ __('translation.employee.phoneNumber') }}<span class="text-danger">
                        *</span></label>
                <input type="text" class="form-control phone" name="phone_number_update" id="phone_number_update" required
                    placeholder="(XXX)XXXX-XXX">
                <input type="text" class="form-control phone" name="phone_update" id="phone_update" hidden value="{{ $employee['phone'] }}">
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="employeeEdit()">{{ __('translation.button.submit') }}</button>
        </div>
    </div>
</form>
<script>
    if (typeof employee == 'undefined') {
        let employee
    }
    employee = <?php echo json_encode($employee); ?>;
</script>
<script src="{{ asset('assets/js/page/employee/employee.edit.js') . '?v=' . time() }}"></script>