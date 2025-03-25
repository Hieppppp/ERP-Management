let dataTableParentProduct;
let dataParentProduct = productDataInDatabase.parent_products;
let dataTableChildProduct;
let dataChildProduct = productDataInDatabase.child_products;
let dataProduct = [];
let dataTableSupplier;
let dataSupplier = productDataInDatabase.suppliers;
let isDataChanged = false;
$(function (e) {
    dataTableParentProduct = $("#parent_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataParentProduct,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: "code",
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
                    return truncateText(data.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
                className: 'text-end'
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return data.quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return data.max_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return data.min_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeParentProduct('${data.id}')"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
        ],
        "createdRow": function (row, data, dataIndex) {
            applyQuantityClasses(row, data?.quantity ?? 0, data.min_quantity);
        }
    });
    dataTableChildProduct = $("#child_product_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataChildProduct,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: "code",
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
                    return truncateText(data.name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.category_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return `${data.unit_price}$`;
                },
                className: 'text-end'
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.unit_name, 20);
                },
            },
            {
                data: function (data, type, row) {
                    return null
                },
            },
            {
                data: function (data, type, row) {
                    return data.max_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return data.min_quantity;
                },
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeChildProduct('${data.id}')"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
        ],
    });
    dataTableSupplier = $("#supplier_datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        responsive: false,
        language: datatableLanguage(),
        data: dataSupplier,
        search: false,
        paging: false,
        info: false,
        ordering: false,
        searching: false,
        columns: [
            {
                data: "code",
                name: "code",
            },
            {
                data: function (data, type, row) {
                    return `<img src="${data.logo_url ? data.logo_url : '/assets/images/no-image.png'}" width="50" height="50" class="img-overlay-light-box">`
                },
                name: "logo",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.name, 20);
                },
                name: "name",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.email);
                },
                name: "email",
            },
            {
                data: "phone",
                name: "phone",
            },
            {
                data: function (data, type, row) {
                    return truncateText(data.address);
                },
                name: "address",
            },
            {
                data: function (data, type, row) {
                    return `<input type="number" class="form-control" name="price[]" value="${data?.pivot?.unit_cost}" required id='price${data.id}'>`;
                }
            },
            {
                data: function (data, type, row) {
                    return data?.pivot?.sku ? data?.pivot?.sku : '';
                }
            },
            {
                data: function (data, type, row) {
                    return `
                    <button type="button" class="btn btn-danger fs-14 text-white edit-icn" onclick="removeSupplier('${data.id}', this)"><i class="fe fe-trash"></i></button>`;
                },
            },
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'min-w-image text-center',
                targets: 1
            },
            {
                className: 'max-width-address',
                targets: 5
            },
        ],
    });
});
let productIdInDatabase = $('#productIdInDatabase').val();
$(document).ready(function () {
    $("#product_edit").validate({
        onfocusout: false,
        rules: {
            name: {
                required: true,
                noSpecialChars: true,
                validate: [`unique:products,name,${productIdInDatabase},id,deleted_at,NULL`, trans('translation.product.name')]
            },
            unit_id: {
                required: true,
            },
            min_quantity: {
                number: true,
                required: true,
                min: 0,
                max: function () {
                    return $('#max_quantity').val() ? parseFloat($('#max_quantity').val()) : Infinity
                }
            },
            max_quantity: {
                number: true,
                required: true,
                min: function () {
                    return $('#min_quantity').val() ? parseFloat($('#min_quantity').val()) : 0; // Giá trị của "a" phải lớn hơn giá trị của "b"
                }
            },
            tax_ids: {
                required: true
            },
            category_id: {
                required: true
            },
            unit_price: {
                required: true,
                step: 0.0001
            },
            "price[]": {
                required: true
            }
        },
        messages: {
            unit_price: {
                step: trans('validation.please_enter_up_to_digits_after_the_decimal_point', { number: 4 })
            }
        }
    });
    initAjaxSelect2('category_id', '/api/v1/category/search', false, productDataInDatabase?.category_id, productDataInDatabase?.category_name);
    let taxes = [];
    if (productDataInDatabase?.taxes) {
        taxes = productDataInDatabase?.taxes.map(function (item) {
            return {
                id: item.id,
                value: item.code
            }
        })
    }
    initAjaxSelect2('tax_ids', '/api/v1/tax/search', false, taxes);
    initAjaxSelect2('unit_id', '/api/v1/unit/search', false, productDataInDatabase?.unit_id, productDataInDatabase?.unit_name);
    $('input, textarea, select').on('change', function (event) {
        isDataChanged = true
    });
});


$(`#unit_id`).on('change', function () {
    if ($(this).valid()) {
        $('#unit_id-error').hide();
    }
})

$(`#tax_ids`).on('change', function () {
    if ($(this).valid()) {
        $('#tax_ids-error').hide();
    }
})
$('#category_id').on('change', function (data) {
    if ($(this).valid()) {
        $('#category_id-error').hide();
    }
})
$('#imagePreview').on('click', function () {
    $('#profile-img-file-input').click();
});

function createProductImage (base64) {
    let productImageHtml = `
    <div class="phone-img">
        <img src="${base64}"
            alt="image">
        <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-x x-square-add remove-product">
                <line x1="18" y1="6" x2="6" y2="18">
                </line>
                <line x1="6" y1="6" x2="18" y2="18">
                </line>
            </svg></a>
    </div>`;
    $('#product-image').append(productImageHtml);
}

function showModalCreate (page) {
    if (page == 'category') {
        showSmallModal(trans('translation.category.create'), `/category/create`);
    }
    if (page == 'tax') {
        showSmallModal(trans('translation.tax.taxCreate'), `/tax/create`);
    }
    if (page == 'unit') {
        showSmallModal(trans('translation.unit.create'), `/unit/create`);
    }
}

function selectModal () {
    var activeElement = $('#navId .active');
    if (activeElement.length > 0) {
        var activeId = activeElement.attr('id');
        if (activeId == 'tabParentProductLink') {
            showLargeModal(trans('translation.product.selectParentProduct'), '/product/select')
            dataProduct = dataParentProduct;
        }
        if (activeId == 'tabChildProductLink') {
            showLargeModal(trans('translation.product.selectChildProduct'), '/product/select')
            dataProduct = dataChildProduct;
        }
        if (activeId == 'tabSupplierLink') {
            showLargeModal(trans('translation.product.selectSupplier'), '/supplier/select')
            dataProduct = []
        }
    }
}

function refreshProduct (data) {
    var activeElement = $('#navId .active');
    if (activeElement.length > 0) {
        var activeId = activeElement.attr('id');
        if (activeId == 'tabParentProductLink') {
            if (data != dataParentProduct) {
                isDataChanged = true;
            }
            dataParentProduct = data;
            dataTableParentProduct.clear();
            dataTableParentProduct.rows.add(dataParentProduct);
            dataTableParentProduct.draw();
        }
        if (activeId == 'tabChildProductLink') {
            if (data != dataChildProduct) {
                isDataChanged = true;
            }
            dataChildProduct = data;
            dataTableChildProduct.clear();
            dataTableChildProduct.rows.add(dataChildProduct);
            dataTableChildProduct.draw();
        }
        closeLargeModal();
    }
}

function refreshSupplier (data) {
    if (data !== dataSupplier) isDataChanged = true;

    const currentDataMap = new Set(dataSupplier.map(item => item.id));
    const newDataMap = new Set(data.map(newItem => {
        if (!currentDataMap.has(newItem.id)) {
            dataTableSupplier.row.add(newItem).draw();
        }
        return newItem.id;
    }));

    dataSupplier.forEach(oldItem => {
        if (!newDataMap.has(oldItem.id)) {
            dataTableSupplier.row(idx => dataSupplier[idx].id === oldItem.id).remove().draw();
        }
    });

    dataSupplier = data;
    dataTableSupplier.draw();
    closeLargeModal();
}

function removeParentProduct (id) {
    isDataChanged = true;
    dataParentProduct = dataParentProduct.filter(el => el.id != id);
    refreshProduct(dataParentProduct);
}

function removeChildProduct (id) {
    isDataChanged = true;
    dataChildProduct = dataChildProduct.filter(el => el.id != id);
    refreshProduct(dataParentProduct);
}

function removeSupplier (id, e) {
    isDataChanged = true;
    dataSupplier = dataSupplier.filter(el => el.id != id);
    var row = $(e).closest('tr');
    dataTableSupplier.row(row).remove().draw();
}


function productEdit () {
    showLoadingSpinner()
    setTimeout(() => {
        if ($('#product_edit').valid()) {
            var unitCost = $("input[name='price[]']");
            var allHaveValues = true;
            unitCost.each(function () {
                if (isNaN(parseFloat($(this).val()))) {
                    allHaveValues = false;
                    return false;
                }
            });
            if (!allHaveValues) {
                $('#tabSupplierLink').tab('show');
                if (!$('#product_edit').valid()) {
                    hideLoadingSpinner()
                    return
                }
            }
            var form = $('#product_edit')[0];
            var formData = new FormData(form);
            if (dataSupplier.length > 0) {
                dataSupplier.forEach((item, index) => {
                    formData.append(`suppliers[${index}][id]`, item.id);
                    formData.append(`suppliers[${index}][price]`, $(`#price${item.id}`).val());
                });
            }
            if (dataChildProduct.length > 0) {
                dataChildProduct.forEach((item, index) => {
                    formData.append(`child_products[]`, item.id);
                });
            }
            if (dataParentProduct.length > 0) {
                dataParentProduct.forEach((item, index) => {
                    formData.append(`parent_products[]`, item.id);
                });
            }
            $.ajax({
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: `/api/v1/product/${productIdInDatabase}`,
                method: 'POST',
                data: formData,
                success: function (response) {
                    hideLoadingSpinner()
                    window.location.href = "/product";
                    notification("success", trans("message.success"));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    hideLoadingSpinner()
                    notification("error", trans("message.error"));
                }
            });
        } else {
            hideLoadingSpinner()
        }
    }, 100);
}

function back () {
    if (isDataChanged) {
        showBackModal('/product')
    } else {
        window.location.href = "/product";
    }
}
