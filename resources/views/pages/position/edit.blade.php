<form id="position_update" class="jquery-validate-form">
    @csrf
    <input type="text" name="positionId" id="positionId" value="{{ $position['id'] }}" hidden>
    <div class="row">
        <div class="col-xl-6  form-group">
            <label for="name">{{ __('translation.position.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $position['name'] }}">
        </div>
        <div class="col-xl-6  form-group">
            <label for="description">{{ __('translation.position.description') }}</label>
            <input type="text" class="form-control" name="description" id="description"
                value="{{ $position['description'] }}">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="editPosition()">{{ __('translation.button.submit') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/position/position.edit.js') }}"></script>
