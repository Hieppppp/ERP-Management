<form id="position_create" class="jquery-validate-form" method="POST" action="{{ url('position') }}">
    @csrf
    <div class="row">
        <div class="col-xl-6 form-group">
            <label for="name">{{ __('translation.position.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="col-xl-6 form-group">
            <label for="description">{{ __('translation.position.description') }}</label>
            <input type="text" class="form-control" name="description" id="description">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="createPosition()">{{ __('translation.button.create') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/position/position.create.js') }}"></script>
