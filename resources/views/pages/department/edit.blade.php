<form id="department_update" class="jquery-validate-form">
    @csrf
    <input type="text" name="departmentId" id="departmentId" value="{{ $department['id'] }}" hidden>
    <div class="row">
        <div class="col-xl-6  form-group">
            <label for="name">{{ __('translation.department.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $department['name'] }}">
        </div>
        <div class="col-xl-6  form-group">
            <label for="description">{{ __('translation.department.description') }}</label>
            <input type="text" class="form-control" name="description" id="description"
                value="{{ $department['description'] }}">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="editDepartment()">{{ __('translation.button.submit') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/department/department.edit.js') }}"></script>
