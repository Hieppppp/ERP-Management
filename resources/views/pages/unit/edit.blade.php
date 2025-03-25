<form id="unit_update" class="jquery-validate-form" method="POST" action="{{ route('unit.update', $unit['id']) }}">
    @csrf
    @method('PUT')
    <input type="text" id="unitId" value="{{ $unit['id'] }}" hidden>
    <div class="row">
        <div class="col-md-12 col-sm-12 row">
            <div class="col-xl-6 form-group">
                <label for="name">{{ __('translation.unit.name') }}<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $unit['name'] }}">
            </div>
            <div class="col-xl-6 form-group">
                <label for="symbol">{{ __('translation.unit.symbol') }}<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="symbol" id="symbol" value="{{ $unit['symbol'] }}">
            </div>
            <div class="form-group col-xl-12"><label for="description">{{ __('translation.unit.description') }}</label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="5">{{ $unit['description'] }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <button onclick="editUnit()" class="btn btn-primary"
                type="button">{{ __('translation.button.submit') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/unit/unit.edit.js') }}"></script>
