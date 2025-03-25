<form id="category_update" class="jquery-validate-form">
    @csrf
    <input type="text" name="categoryId" id="categoryId" value="{{ $category['id'] }}" hidden>
    <div class="row">
        <div class="col-xl-6  form-group">
            <label for="name">{{ __('translation.category.name') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $category['name'] }}">
        </div>
        <div class="col-xl-6  form-group">
            <label for="description">{{ __('translation.category.description') }}</label>
            <input type="text" class="form-control" name="description" id="description"
                value="{{ $category['description'] }}">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="button"
                onclick="editCategory()">{{ __('translation.button.submit') }}</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/page/category/category.edit.js') }}"></script>
