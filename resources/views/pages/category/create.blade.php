<form id="category_create" class="jquery-validate-form" method="POST" action="{{ url('category') }}">
    @csrf
    <div class="row">
        <div class="col-xl-6 form-group">
            <label for="name">{{ __('translation.category.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="col-xl-6 form-group">
            <label for="description">{{ __('translation.category.description') }}</label>
            <input type="text" class="form-control" name="description" id="description">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="createCategory()">{{ __('translation.button.create') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/category/category.create.js') }}"></script>
