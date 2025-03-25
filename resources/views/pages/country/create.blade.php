<form id="country_create" class="jquery-validate-form" method="POST" action="{{ url('country') }}">
    @csrf
    <div class="row">
        <div class="form-group">
            <label for="name">{{ __('translation.menu.country') }}<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="form-group">
            <button class="btn btn-primary d-flex" type="button"
                onclick="countryCreate()">{{ __('translation.button.create') }}</button>
        </div>
    </div>
</form>
<script>
    $('input, textarea').on('input', function() {
        $(this).val($(this).val().replace(/^\s+/, ''));
    });
    $("#country_create").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                maxlength: 100,
                validate: [`unique:countries,name`, trans('translation.country.name')],
            },
        }
    });

    function countryCreate() {
        showLoadingSpinner();
        setTimeout(() => {
            if ($("#country_create").valid()) {
                var formData = $('#country_create').serialize();
                $.ajax({
                    url: '/api/v1/country',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        notification("success", trans("message.success"));
                        closeSmallModalV2();
                        hideLoadingSpinner();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        notification("error", trans("message.error"));
                        hideLoadingSpinner();
                    }
                });
            } else {
                hideLoadingSpinner();
            }
        }, 100);
    }
</script>
