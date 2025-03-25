let telInput;
$(document).ready(function () {
    let phoneNumber = document.querySelector("#phone_number");
    telInput = window.intlTelInput(phoneNumber, {
        initialCountry: "ca",
        separateDialCode: true,
        showFlags: false,
        utilsScript: "/assets/plugins/intl-tel-input/build/js/utils.js"
    });
    telInput.setNumber(supplier.phone);
    let productTable = $("#product-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[0, "desc"]],
        language: datatableLanguage(),
        data: products,
        columns: [
            {
                data: "id",
                name: "id",
            },
            {
                data: function (data, type, row) {
                    let imageUrl = '/assets/images/no-image.png';
                    if (data.images[0]) {
                        imageUrl = data.images[0]?.image_url;
                    }
                    return `<img src="${imageUrl}" width="50" height="50" class="img-overlay-light-box">`
                },
            },
            {
                data: function (data, type, row) {
                    return `<a href="/product/${data.id}" target="_blank">${truncateText(data.name, 20)}</a>`;
                },
                name: "name",
            },
            {
                data: 'sku',
            },
            {
                data: function (data, type, row) {
                    return data.total_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
                name: "unit_price",
                className: 'text-end'
            },
            {
                data: 'unit_cost',
                className: 'text-end'
            },
            {
                data: function (data, type, row) {
                    if (data?.category) {
                        return truncateText(data?.category?.name, 20);
                    }
                    return '';
                },
                name: "category_name",
            },
            {
                data: function (data, type, row) {
                    if (data?.unit) {
                        return truncateText(`${data?.unit?.name, 20} (${data?.unit?.symbol})`);
                    }
                    return '';
                },
                name: "unit_name",
            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
        ],
        initComplete: function (settings) {
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
            })
        },
    });
    $("#product-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            productTable.search("").draw();
        }
    });
});
