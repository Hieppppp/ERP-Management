<form id="department_create" class="jquery-validate-form" method="POST" action="{{ url('department') }}">
    @csrf
    <div class="row">
        <div class="col-xl-6 form-group">
            <label for="name">{{ __('translation.department.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="col-xl-6 form-group">
            <label for="description">{{ __('translation.department.description') }}</label>
            <input type="text" class="form-control" name="description" id="description">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="createDepartment()">{{ __('translation.button.create') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/department/department.create.js') }}"></script>
