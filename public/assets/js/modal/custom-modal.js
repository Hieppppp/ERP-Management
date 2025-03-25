let loading = `
<div class="d-flex justify-content-center">
                <div class="spinner-border " style="color: var(--primary-bg-color) !important;" role="status">
                    <span class="visually-hidden" >Loading...</span>
                </div>
            </div>`;
function showSmallModal (header = "", url = "") {
    if (url) {
        $(`#smallModal`).modal('show');
        $(`#smallModal-header`).text(header ? header : trans('translation.selectItems'));
        $(`#smallModal-body`).html(loading);
        $.ajax({
            type: "GET",
            url: url,
            success: function (res) {
                $(`#smallModal-body`).html(res);
            },
            error: function (xhr, err) {
                closeSmallModal()
                if (xhr?.responseJSON) {
                    if (Array.isArray(xhr.responseJSON?.message)) {
                        return notification('error', xhr.responseJSON?.message[0]);
                    }
                }
                notification('error', trans(
                    "message.error"
                ));
            },
        });
    }
}

function showLargeModal (header = "", url = "") {
    if (url) {
        $(`#largeModal`).modal('show');
        $(`#largeModal-header`).text(header ? header : trans('translation.selectItems'));
        $(`#largeModal-body`).html(loading);
        $.ajax({
            type: "GET",
            url: url,
            success: function (res) {
                $(`#largeModal-body`).html(res);
            },
            error: function (xhr, err) {
                closeLargeModal()
                if (xhr?.responseJSON) {
                    if (Array.isArray(xhr.responseJSON?.message)) {
                        return notification('error', xhr.responseJSON?.message[0]);
                    }
                }
                notification('error', trans(
                    "message.error"
                ));
            },
        });
    }
}

function showLargeModal_2 (header = "", url = "") {
    if (url) {
        $(`#largeModal_2`).modal('show');
        $(`#largeModal_2-header`).text(header ? header : trans('translation.selectItems'));
        $(`#largeModal_2-body`).html(loading);
        $.ajax({
            type: "GET",
            url: url,
            success: function (res) {
                $(`#largeModal_2-body`).html(res);
            },
            error: function (xhr, err) {
                closeLargeModal_2()
                if (xhr?.responseJSON) {
                    if (Array.isArray(xhr.responseJSON?.message)) {
                        return notification('error', xhr.responseJSON?.message[0]);
                    }
                }
                notification('error', trans(
                    "message.error"
                ));
            },
        });
    }
}

function showDeleteModal (nameFunction = "") {
    $(`#deleteModal`).modal('show');
    $(`#deleteModalYes`).attr('onclick', nameFunction);
}

function closeSmallModal () {
    $(`#smallModal`).modal('hide');
}

function closeLargeModal () {
    $(`#largeModal`).modal('hide');
}

function closeLargeModal_2 () {
    $(`#largeModal_2`).modal('hide');
}

function closeDeleteModal () {
    $(`#deleteModal`).modal('hide');
}
var modalStack = [];
let isAddModalStack = true;
$(document).ready(function () {

    $('.modal').on('show.bs.modal', function (e) {
        if (!$(e.target).hasClass('fc-datepicker')) {
            var currentModal = $(this);
            if (modalStack.length > 0) {
                var previousModal = modalStack[modalStack.length - 1];
                previousModal.modal('hide');
            }
            modalStack.push(currentModal);
            isAddModalStack = true
        }

    });

    $('.modal').on('hidden.bs.modal', function (e) {
        if (!$(e.target).hasClass('fc-datepicker')) {
            if (!$('.modal.show').length) {
                modalStack.pop();
                if ($(this).attr('id') === 'modalConfirm') {
                    modalStack = [];
                    return;
                }
                if (modalStack.length > 0) {
                    var previousModal = modalStack[modalStack.length - 1];
                    if (isAddModalStack) {
                        modalStack = []
                        previousModal.modal('show');
                        isAddModalStack = false;
                    }
                }
            }
        }
    });
});


function showSmallModal_v2 (header = "", url = "") {
    if (url) {
        $(`#smallModal_v2`).modal('show');
        $(`#smallModal_v2-header`).text(header ? header : trans('translation.selectItems'));
        $(`#smallModal_v2-body`).html(loading);
        $.ajax({
            type: "GET",
            url: url,
            success: function (res) {
                $(`#smallModal_v2-body`).html(res);
            },
            error: function (xhr, err) {
                closeSmallModalV2()
                if (xhr?.responseJSON) {
                    if (Array.isArray(xhr.responseJSON?.message)) {
                        return notification('error', xhr.responseJSON?.message[0]);
                    }
                }
                notification('error', trans(
                    "message.error"
                ));
            },
        });
    }
}

function showBackModal (redirectUrl) {
    if (redirectUrl) {
        $(`#modalConfirmBack`).modal('show');
        $(`#redirectUrl`).attr('href', redirectUrl);
    }

}

function closeSmallModalV2 () {
    $(`#smallModal_v2`).modal('hide');
}

function showModalLightBox (e) {
    let image_url = $(e).attr('src');
    $('#modalImage #img-light-box').attr('src', image_url);
    $(`#modalImage`).modal('show');
}

function showConfirmModal (nameFunction, header = "", message = "") {
    $(`#modalConfirm`).modal('show');
    $(`#confirmModal-title`).text(header ? header : trans('translation.modal.confirm'));
    $(`#confirmModal-body`).html(`<p>${message ? message : trans('message.confirm')}</p>`);
    $(`#submitConfirm`).attr('onclick', nameFunction);
}

function closeConfirmModal () {
    $(`#modalConfirm`).modal('hide');

}

