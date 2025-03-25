<form id="unit_create" class="jquery-validate-form" method="POST" action="{{ url('unit') }}">
    @csrf
    <div class="row">
        <div class="col-xl-6 form-group">
            <label for="name">{{ __('translation.unit.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="col-xl-6 form-group">
            <label for="symbol">{{ __('translation.unit.symbol') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="symbol" id="symbol">
        </div>
        <div class="form-group col-xl-12"><label for="description">{{ __('translation.unit.description') }}</label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="5"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="createUnit()">{{ __('translation.button.create') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/unit/unit.create.js') }}"></script>
