
$(document).ready(function () {
    let formSubmitted = false;
    $('form').on('submit', function (event) {
        if (formSubmitted) {
            formSubmitted = false
            return true;
        }
        event.preventDefault();
        showLoadingSpinner();
        setTimeout(() => {
            if (!$(this).valid()) {
                hideLoadingSpinner();
            } else {
                formSubmitted = true;
                $(this).submit();
            }
        }, 100);
    });
    $('input, textarea').on('input', function () {
        var type = $(this).attr('type');
        var excludedTypes = ['number', 'file', 'checkbox', 'radio', 'password', 'hidden', 'color', 'date', 'time', 'datetime-local', 'month', 'week'];
        if (!excludedTypes.includes(type)) {
            $(this).val($(this).val().trimStart());
        }
    });
    $('input, textarea').on('blur', function () {
        $(this).val($(this).val().trim());
    });
});

function showLoadingSpinner () {
    $("#loading-overlay").show();
}

async function hideLoadingSpinner () {
    $("#loading-overlay").hide();
}
