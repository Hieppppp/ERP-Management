$(document).ready(function () {
    $("#inventoryForm").validate({
        onfocusout: false,
        rules: {
            noProd: {
                required: true,
            },
            nomFourProd: {
                required: true,
            },
            mouvRaison: {
                required: true,
            },
            adjustment: {
                required: true,
                validDecimal: true
            }
        },
        messages: {
            noProd: {
                required: trans("validation.required"),
            },
            nomFourProd: {
                required: trans("validation.required")
            },
            mouvRaison: {
                required: trans("validation.required"),
            },
            adjustment: {
                required: trans("validation.required"),
                validDecimal : trans("validation.invaliRegex", { field: trans('translation.inventory.adjustment')  })
            },
        }
    });

    inputTrim([
        'mouvRaison'
    ]);
});

let dataProduct;
let dataSupplier;
$('#noProd').on('click', function() {
    showLargeModal(trans('translation.inventory.selectProduct'), "/product/select?callback=renderDataProduct");
});

function renderDataProduct(data) {
    if (data) {
        dataProduct = data
        $('#descrAbreFranc').val(data.descrAbreFranc);
        $('#Avertissement').val(data.Avertissement);
        $('#noProd').val(data.noProd);
        $('#inventoryQuantity :input').each(function() {
            if ($(this).is('input') || $(this).is('select')) {
                var fieldName = $(this).attr('name');
                if (fieldName == 'enAchat') {
                    var fieldValue = data.enAchatOne;
                } else{
                    var fieldValue = data[fieldName];
                }
                if (fieldValue !== undefined) {
                    $(this).val(fieldValue);
                } else {
                    $(this).val(''); 
                }
            }
        });
        $('#nomFourProd').css({
            'background-color': '#fff'
        });
        closeLargeModal();
        $('#noProd').valid();
    };
}
$('#nomFourProd').on('click', function() {
    if (dataProduct && dataProduct.noPID) {
        showLargeModal(trans('translation.inventory.selectSupplier'), `/fourn-prod/select?callback=renderDataSupplier&&productId=${dataProduct.noPID}`);
    }
});

function renderDataSupplier(data) {
    if (data) {
        dataSupplier = data;
        showDataSupplier(data);
        closeLargeModal();
        $('#nomFourProd').valid();
    };
}

function showDataSupplier(data) {
    $('#DescLot').val(data.DescLot);
    $('#QteparLot').val(data.QteparLot);
    $('#supplierOrClient :input').each(function() {
        if ($(this).is('input')) {
            var fieldName = $(this).attr('name');
            var fieldValue = data[fieldName];
            if (fieldValue !== undefined) {
                $(this).val(fieldValue);
            } else {
                $(this).val('');
            }
        }
    });
}

function addInventory() {
    if ($('#inventoryForm').valid()) {
        let dataSupplierUpdate = {
            noProd : $('#noProd').val(),
            descrAbreFranc : $('#descrAbreFranc').val(),
            invProd: parseFloat($('#invProd').val()) + parseFloat($('#adjustment').val()),
            QtedeLot: parseFloat($('#invProd').val()) + parseFloat($('#adjustment').val()),
        }
        let dataMouveInv = {
            mouvQte: parseFloat($('#adjustment').val()),
            mouvRaison: $('#mouvRaison').val(),
            enInv: parseFloat($('#enInv').val())
        };
        $.ajax({
            url: `/api/fourn-prod/${dataSupplier.noFourProdID}/edit`,
            type: "POST",
            dataType: "json",
            data: {
                supplier: dataSupplierUpdate,
                mouveInv: dataMouveInv
            },
            async: false,
            success: function(response) {
                $('#enInv').val(response.data.product);
                $('#invProd').val(response.data.supplier.invProd);
                $('#adjustment').val('');
                $('#mouvRaison').val('');
                notification("success", trans("message.createSuccess"));
            },
            error: function () {
                notification('error', trans('message.createFailed'));
            },
        });
    }
}

function getData(Id) {
    var allDataFolder = $(`#${Id} input[type="checkbox"]:checked, #${Id} input[type="text"], #${Id} input[type="number"], #${Id} select, #${Id} textarea`);
    var formObjectFolder = {};
    allDataFolder.each(function(){
        formObjectFolder[$(this).attr('name')] = $(this).val();
    });
    return formObjectFolder;
}
