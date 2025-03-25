<form id="city_create" class="jquery-validate-form" method="POST" action="{{ url('tax') }}">
    @csrf
    <div class="row">
        <div class="row col-xl-12">
            <div class="col-xl-6 form-group">
                <label for="country_id">{{ __('translation.menu.country') }}<span class="text-danger">
                        *</span></label>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="country_id" id="country_id"
                        required></select>
                </div>
            </div>
            <div class="col-xl-6 form-group">
                <label for="province_id">{{ __('translation.menu.province') }}<span class="text-danger">
                        *</span></label>
                <div class="d-flex flex-column-reverse">
                    <select class="form-control select2-show-search form-select" name="province_id" id="province_id"
                        required disabled></select>
                </div>
            </div>
            <div class="col-xl-12 form-group">
                <label for="name">{{ __('translation.menu.city') }}<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group">
                <button class="btn btn-primary d-flex" type="button"
                    onclick="cityCreate()">{{ __('translation.button.create') }}</button>
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/city/city.create.js') }}"></script>
