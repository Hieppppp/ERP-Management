<form id="shelve_edit" class="jquery-validate-form" method="POST" action="{{ route('shelve.update', $shelve['id']) }}">
    @csrf
    @method('PUT')
    <input type="text" hidden value="{{ $shelve['id'] }}" id="shelveId">
    <div class="row">
        <div class="col-md-12 col-sm-12 row">
            <div class="col-xl-6 col-lg-12 form-group">
                <label for="name">{{ __('translation.shelve.name') }}<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $shelve['name'] }}">
            </div>
            <div class="col-xl-6 col-lg-12 form-group">
                <label for="warehouse_id">{{ __('translation.shelve.warehouse') }}<span class="text-danger">
                        *</span></label>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="warehouse_id"
                        id="warehouse_id"></select>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 form-group">
                <label for="location">{{ __('translation.shelve.location') }}<span class="text-danger"> *</span></label>
                <textarea class="form-control" name="location" id="location" cols="30" rows="5">{{ $shelve['location'] }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="editShelve()">{{ __('translation.button.submit') }}</button>
        </div>
    </div>
</form>
<script>
    if (typeof shelve == 'undefined') {
        let shelve
    }
    shelve = <?php echo json_encode($shelve); ?>;
</script>
<script src="{{ asset('assets/js/page/shelve/shelve.edit.js') }}"></script>
