<?php
$userPermissions = '[]';
$isAdmin = 0;

if (Auth::user()) {
    $userPermissions = PermissionRole::getUserPermissions(Auth::user());
    $isAdmin = in_array(Auth::user()->role, [UserRole::SUPPER_ADMIN, UserRole::ADMIN]) ? 1 : 0;
}
$userPermissions = json_encode($userPermissions);
?>

<script>
    const userPermissions = JSON.parse(`<?php echo $userPermissions; ?>`);
    const isAdmin = {{ $isAdmin }}
    window.translations = {!! $translation !!};

    function trans(key, replace = {}) {
        let translation = key.split('.').reduce((t, i) => t[i] || key, window.translations);

        for (var placeholder in replace) {
            translation = translation.replace(`{${placeholder}}`, replace[placeholder]);
            translation = translation.replace(`:${placeholder}`, replace[placeholder]);
        }

        return translation;
    }

    function hasPermission(permission) {
        if (isAdmin) {
            return true;
        }

        if (userPermissions.includes(permission)) {
            return true;
        }
        return false;
    }

    function numberFormat(number, decimals = 0, decimalSeparator = '.', thousandsSeparator = ',') {
        let num = parseFloat(number);
        if (isNaN(num)) {
            return 0;
        }
        num = num.toFixed(decimals).toString();
        let parts = num.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
        return parts.join(decimalSeparator);
    }

    function dateFormat(inputDate) {
        if (!inputDate) {
            return '';
        }
        const date = new Date(inputDate);
        if (isNaN(date)) {
            return '';
        }
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    function datatableLanguage() {
        return {
            searchPlaceholder: `${trans("translation.datatable.search")}`,
            sSearch: "",
            processing: `<div class="d-flex justify-content-center align-items-center" style="position:absolute; top:0;left:0;width:100%;height:100%"><div class="spinner-border text-primary" style="width: 2rem; height: 2rem;" role="status"><span class="sr-only">Loading...</span></div></div>`,
            paginate: {
                next: `»`,
                previous: `«`,
            },
            info: `${trans("translation.datatable.showing")} _START_ ${trans("translation.datatable.to")} _END_ ${trans("translation.datatable.of")} _TOTAL_ ${trans("translation.datatable.entries")}`,
            infoFiltered: `(_MAX_ ${trans("translation.datatable.totalEntries")})`,
            emptyTable: `${trans("translation.datatable.noData")}`,
            lengthMenu: `_MENU_`,
        }
    }

    function setDatatableFilters(filterConfigs = []) {
        const api = filterConfigs.api;
        const tableId = filterConfigs.tableId;

        if (!api || !tableId) {
            throw new Error('missing config');
        }

        var theadSecondRow = '<tr class="dataTables_filter">';
        $(`#${tableId}`).find('thead tr th').each(function(idx) {
            theadSecondRow += '<td class="form-control-' + idx + '"></td>';
        });
        theadSecondRow += '</tr>';

        $(theadSecondRow).insertAfter($(`#${tableId}`).find('thead tr'));

        api.columns().every(function(index) {
            const column = this;
            const columnHeader = $(column.header());

            if (columnHeader.hasClass('text-filter')) {
                let inputType = 'text';
                if (columnHeader.hasClass('number-filter')) {
                    inputType = 'number';
                }
                const searchInput =
                    `<input type="${inputType}" style="min-width: 100px" class="form-control" id="filter-${tableId}-${index}" value="${column.search()}">`
                $(`#${tableId} td.form-control-${index}`).html(searchInput);
                $(`#filter-${tableId}-${index}`).on('change clear', function() {
                    if (column.search() !== $(this).val()) {
                        column.search($(this).val()).draw();
                    }
                })
            }

            if (columnHeader.hasClass('select-filter')) {
                const columnId = columnHeader.attr('id');
                if (!filterConfigs.select?.[columnId]) {
                    throw new Error(`Missing config for columns ${index}`);
                }
                const config = filterConfigs.select[columnId];
                if (!config.dataType) {
                    throw new Error(
                        `Missing config [dataType] for select of column: ${index}, id: ${columnId}`);
                }
                const dataType = config.dataType;
                if (!['fix', 'ajax'].includes(dataType)) {
                    throw new Error(`Invalid dataType "${dataType}" column: ${index}, id: ${columnId}`);
                }
                const selectId = `${tableId}-select-${index}`;

                const select = $(`<select id="${selectId}" class="form-control select2"></select>`);
                $(`#${tableId} td.form-control-${index}`).html(select);

                if (config.dataType == 'fix') {
                    if (!config.data) {
                        throw new Error(
                            `Missing config [data] for options of column: ${index}, id: ${columnId}`);
                    }
                    const data = config.data;
                    data.forEach((value, i) => {
                        if (!value.id || !value.text) {
                            throw new Error(
                                `Data must have id, text - column: ${index}, id: ${columnId}`);
                        }
                    })
                    $(`#${selectId}`).select2({
                        minimumInputLength: 0,
                        data: data,
                        placeholder: config.placeholder || trans('translation.placeHolder.search'),
                        allowClear: true
                    });
                    $(`#${selectId}`).val(column.search() ?? '').trigger("change");
                    $(`#${selectId}`).on("change", function() {
                        column.search($(this).val() ?? '').draw();
                    });
                }
                if (config.dataType == 'ajax') {
                    if (!config.url) {
                        throw new Error(
                            `Missing config [url] for options of column: ${columnId}, id: ${columnId}`);
                    }
                    const url = config.url;
                    $(`#${selectId}`).select2({
                        minimumInputLength: 0,
                        ajax: {
                            url: url,
                            data: function(params) {
                                var query = {
                                    search: params.term || "",
                                    page: params.page || 1,
                                };
                                return query;
                            },
                            dataType: "json",
                        },
                        placeholder: config.placeholder || trans('translation.placeHolder.search'),
                        allowClear: true,
                    });
                    const stateId = `DataTablesSelect_${tableId}_${window.location.pathname}`;
                    $(`#${selectId}`).on("change", function() {
                        if (api.init().stateSave) {
                            let selectState = JSON.parse(localStorage.getItem(stateId));
                            const stateSaveData = {
                                'id': $(this).val(),
                                'text': $(this).find("option:selected").text()
                            }
                            if (!selectState) {
                                selectState = {
                                    [columnId]: stateSaveData
                                }
                            } else {
                                selectState[columnId] = stateSaveData;
                            }
                            localStorage.setItem(stateId, JSON.stringify(selectState));
                        }
                        column.search($(this).val() ?? '').draw();
                    });
                    if (column.search()) {
                        let selectState = JSON.parse(localStorage.getItem(stateId));
                        if (selectState?.[columnId]) {
                            const option = new Option(selectState[columnId].text, selectState[columnId].id,
                                true, true);
                            $(`#${selectId}`).append(option).trigger('change');
                        }
                    }
                }

            }
        })
    }

    function initAjaxSelect2(inputId, ajaxUrl, isNewValueEntered = false, id = '', value = '') {
        let parentModal = $(`#${inputId}`).closest('.modal.fade');
        let parentModalId = '';
        if (parentModal.length) {
            parentModalId = parentModal.attr('id');
        }
        var select2 = $(`#${inputId}`).select2({
            closeOnSelect: $(`#${inputId}`).attr('multiple') ? false : true,
            minimumInputLength: 0,
            ajax: {
                url: ajaxUrl,
                data: function(params) {
                    var query = {
                        search: params.term || "",
                        page: params.page || 1,
                    };
                    return query;
                },
                dataType: "json",
                processResults: function(data) {
                    return {
                        results: $.map(data.results, function(item) {
                            let valueSelected = $(`#${inputId}`).val();
                            let isDisabled = false;

                            if (item?.value) {
                                if (Array.isArray(valueSelected)) {
                                    if (valueSelected.includes(item?.value.toString())) {
                                        isDisabled = true;
                                    }
                                } else {
                                    if (valueSelected === item?.value.toString()) {
                                        isDisabled = true;
                                    }
                                }
                            }
                            return {
                                ...item,
                                disabled: isDisabled,
                                id: item.value,
                                text: item.text
                            };
                        }),
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
            },
            templateResult: function(item) {
                let valueSelected = $(`#${inputId}`).val();
                let isDisabled = false;

                if (item?.value) {
                    if (Array.isArray(valueSelected)) {
                        if (valueSelected.includes(item?.value.toString())) {
                            isDisabled = true;
                        }
                    } else {
                        if (valueSelected === item?.value.toString()) {
                            isDisabled = true;
                        }
                    }
                }
                if (isDisabled) {
                    return $('<span class="checked-option-select2"> ' + item.text +
                        ' <i class="fa fa-check"></i> </span>');
                }
                return item.text;
            },
            placeholder: trans('translation.placeHolder.select'),
            ...(parentModalId !== '' && {
                dropdownParent: $(`#${parentModalId}`)
            })
        }).on('select2:unselect', function(e) {
            $(this).select2('close');
        });

        if (id && value) {
            const option = new Option(value, id, true, true);
            $(`#${inputId}`).append(option).trigger('selected');
        }
        if (Array.isArray(id)) {
            id.forEach(function(item) {
                let option = new Option(item.value, item.id, true, true);
                $(`#${inputId}`).append(option).trigger('change');
            })

        }
        if (isNewValueEntered) {
            $(`#${inputId}`).on('select2:closing', function(e) {
                var $searchField = $(this).data('select2').dropdown.$search || $(this).data('select2')
                    .selection
                    .$search;
                var searchValue = $searchField.val().trim();
                if (searchValue) {
                    let exists = false;
                    $(`#${inputId} option`).each(function() {
                        if ($(this).text().toLowerCase() === searchValue
                            .toLowerCase()) {
                            exists = true;
                            return false;
                        }
                    });
                    if (!exists) {
                        // Add new option
                        var newOption = new Option(searchValue, searchValue, true, true);
                        $(this).append(newOption).trigger('change');
                    }
                }
            });
        }
        if ($(`#${inputId}`).attr('multiple')) {
            $(document).on('click', `#select2-${inputId}-results li`, function() {
                if ($(this).attr('aria-selected')) {
                    $(this).removeAttr('aria-selected');
                    $(this).attr('aria-disabled', 'true');
                    $(this).addClass('checked-option-select2');
                    $(this).append(' <i class="fa fa-check "></i>');
                };
            });
        }
    }

    function inputTrimStart() {
        $('input:not([type="number"]), textarea').on('input', function() {
            $(this).val($(this).val().trimStart());
        });
    }

    function initDatePicker(inputIds) {
        inputIds.forEach((inputId) => {
            let options = {
                showOtherMonths: true,
                selectOtherMonths: true,
            }
            if (Array.isArray(inputId)) {
                if (typeof inputId[1] !== undefined) {
                    options.startDate = inputId[1];
                }
                if (typeof inputId[2] !== undefined) {
                    options.endDate = inputId[2];
                }
                inputId = inputId[0];
            }
            $(`#${inputId}`).datepicker(options);
        })
    }

    function numberFormatInt(data, number) {
        if (!isNaN(Number(data))) {
            data = Number(data);
            let base = 10 ** number;
            let result = Math.round(data * base) / base;
            return result;
        } else {
            return data;
        }
    }

    function formatFloat(data, number) {
        return parseFloat(data.toFixed(number));
    }

    function handleMultipleTabForm(formId, tabList, validator) {
        let tabIndexActive = -1;
        validator.errorList.forEach((error) => {
            const tabId = $(error.element).closest('.tab-pane').attr('id');
            const tabIndex = tabList.indexOf(tabId);
            if (tabIndex !== -1) {
                if (tabIndex < tabIndexActive || tabIndexActive === -1) {
                    tabIndexActive = tabIndex;
                }
            }
        })
        if (tabIndexActive !== -1) {
            $(`#${formId} .tabs-menu4 .nav-link[href="#${tabList[tabIndexActive]}"]`).tab('show');
        }
    }

    //show modal select one
    function addCheckBoxDataTable(row) {
        row.querySelector(':nth-child(1)').innerHTML = `
            <label class="ckbox lh-sm mb-0" style=>
                <input type="checkbox" name="checkBoxSelected"></input>
                <span class="ms-0"></span>
            </label>`;
    }

    function selectOne(tableId) {
        $(`#${tableId}`).on('click', 'tbody tr', function(event) {
            const checkBox = $(this).find('input[name="checkBoxSelected"]');
            if (checkBox.is(':checked')) {
                checkBox.prop('checked', false);
            } else {
                selectOnlyThis(checkBox);
                checkBox.prop('checked', true);
            }
        });
    }

    function selectOnlyThis(id) {
        const myCheckbox = document.getElementsByName(id.attr('name'));
        Array.prototype.forEach.call(myCheckbox, function(el) {
            if (el.value != id.value) {
                el.checked = false;
            }
        });
    }

    function getDataSelected(tableId, dataTable) {
        let data;
        $(`#${tableId} tbody input[type="checkbox"]:checked`).each(function() {
            data = dataTable.row($(this).closest('tr')).data();
        });
        return data;
    }

    // end

    function getFormData(formId) {
        return Object.fromEntries(new FormData($(`#${formId}`)[0]));
    }

    function initSummernote(inputIds) {
        inputIds.forEach((input) => {
            let height = 300;
            let inputId;
            if (typeof input === 'string') {
                inputId = input;
            } else {
                inputId = input.Id;
                height = input.height;
            }
            $(`#${inputId}`).summernote({
                height: height
            });
            $('div.note-editable').css({
                'min-height': 200 + 'px'
            });
        })
    }

    function inputUpperCase(inputId) {
        $(`#${inputId}`).on('input', function() {
            $(this).val($(this).val().toUpperCase());
        })
    }

    function clipTextWithToolTip(text, maxLength) {
        if (text.length > maxLength) {
            return `<span data-bs-original-title="${text}" data-bs-placement="top" data-bs-toggle="tooltip">${text.substring(0, maxLength)}...</span>`;
        } else {
            return text;
        }
    }

    function clipText(text, maxLength) {
        return text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
    }

    function inputTrim(inputIds) {
        inputIds.forEach((inputId) => {
            $(`#${inputId}`).on('change', function() {
                let inputValue = $(this).val();
                $(this).val(inputValue.trim());
                $(this).valid();
            })
        })
    }

    function createFilterRow(filterSettings) {
        const row = $('<div>').addClass('row mb-2');

        const firstCol = $('<div>').addClass('col-md-3');
        const secondCol = $('<div>').addClass('col-md-3');
        const thirdCol = $('<div>').addClass('col-md-5');
        const fourthCol = $('<div>').addClass('col-md-1 d-flex align-items-start');

        const firstSelect = $('<select>').addClass('form-control').append('<option value="">Select Filter</option>');

        $.each(filterSettings, function(key, setting) {
            firstSelect.append($('<option>').val(key).text(setting.text));
        });

        const secondSelect = $('<select>').addClass('form-control').prop('disabled', true);
        let thirdInput = $('<input>').attr('type', 'text').addClass('form-control').prop('disabled', true);
        const removeBtn = $('<button>').addClass('btn btn-danger btn-sm').html('<i class="fe fe-trash"></i>');
        removeBtn.on('click', function() {
            row.remove();
        });

        firstSelect.on('change', function() {
            const selectedKey = $(this).val();
            secondSelect.empty().prop('disabled', true);
            thirdInput.prop('disabled', true);
            if (!selectedKey) {
                thirdInput = $('<input>').attr('type', 'text').addClass('form-control').prop('disabled', true);
                thirdCol.html('').append(thirdInput);
                return;
            }

            const settingInfo = filterSettings[selectedKey];
            if (selectedKey && settingInfo.options) {
                $.each(settingInfo.options, function(index, option) {
                    secondSelect.append($('<option>').val(option.value).text(option.text));
                });
                secondSelect.prop('disabled', false);
                secondSelect.val(secondSelect.find('option').eq(0).val()).change();
            }

            if (selectedKey && settingInfo.type && settingInfo.type === 'select2') {
                const selectId = `filter-select2-${uniqueIdCounter++}`;
                thirdInput = $('<select>').addClass('form-control select2').attr('id', selectId).prop(
                    'disabled', false);
                thirdCol.html('').append(thirdInput);
                initAjaxSelect2(selectId, settingInfo.api);
            } else {
                thirdInput = $('<input>').attr('type', 'text').addClass('form-control').prop('disabled', false);
                if (settingInfo.columns && settingInfo.columns.some(item => item.target === 3)) {
                    const thirdColumnSetting = settingInfo.columns.find(item => item.target === 3);
                    thirdInput = typeof thirdColumnSetting.html === 'function' ? thirdColumnSetting.html() :
                        thirdColumnSetting.html;
                }
                thirdCol.html('').append(thirdInput);
            }
        });

        firstCol.append(firstSelect);
        secondCol.append(secondSelect);
        thirdCol.append(thirdInput);
        fourthCol.append(removeBtn);

        row.append(firstCol, secondCol, thirdCol, fourthCol);

        return row;
    }

    function defaultFilterHandler(column, condition, value) {
        return {
            column,
            condition,
            value
        };
    }

    function getFilterData(containerId, filterSettings) {
        let searchQuery = [];

        $(`#${containerId} .row`).each(function() {
            const row = $(this);
            const key = row.find('select:first').val();
            const condition = row.find('select').eq(1).val();
            let valueInput = row.find('input');
            if (!valueInput.length) {
                valueInput = row.find('select').eq(2);
            }
            const value = valueInput.val();

            if (key && condition && value) {
                const filterConfig = filterSettings[key];
                const column = filterConfig.value;
                const handler = filterConfig.handler || defaultFilterHandler;
                const filterData = handler(column, condition, value);

                searchQuery.push(filterData);
            }
        });
        return {
            filters: searchQuery
        };
    }

    function initFilter(options) {
        const addRuleBtnId = options.addRuleBtnId;
        const containerId = options.containerId;
        const setting = options.setting;
        $(`#${addRuleBtnId}`).on('click', function() {
            $(`#${containerId}`).append(createFilterRow(setting));
        });

        $(`#${containerId}`).append(createFilterRow(setting));
    }

    function truncateText(text, limit = 50, isReturnHtml = true) {
        if (typeof text !== 'string') {
            return text;
        }
        // Check if the text length is within the limit
        if (text.length <= limit) {
            return isReturnHtml ? `<div>${text}</div>` : text;
        }

        // Find the last space before the limit
        let truncated = text.slice(0, limit);
        let lastSpace = truncated.lastIndexOf(' ');

        // If a space is found, cut the text there, otherwise, cut at the limit
        if (lastSpace > -1) {
            truncated = truncated.slice(0, lastSpace);
        }
        return isReturnHtml ? `<div title = "${text}">${truncated}...</div>` : `${truncated}...`;
    }

    function applyQuantityClasses(row, quantity, minQuantity) {
        if (quantity <= minQuantity) {
            $(row).find("td").addClass("quantity-alert");
        } else if (quantity <= minQuantity + 10) {
            $(row).find("td").addClass("quantity-orange");
        } else if (quantity <= minQuantity + 20) {
            $(row).find("td").addClass("quantity-warning");
        }
    }

    function initQuillEditor(id) {
        var toolbarOptions = [
            [{
                'header': [1, 2, 3, 4, 5, 6, false]
            }],
            [{
                'font': []
            }],
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{
                'header': 1
            }, {
                'header': 2
            }],
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'script': 'sub'
            }, {
                'script': 'super'
            }],
            [{
                'indent': '-1'
            }, {
                'indent': '+1'
            }],
            [{
                'direction': 'rtl'
            }],
            [{
                'size': ['small', false, 'large', 'huge']
            }],
            [{
                'color': []
            }, {
                'background': []
            }],
            [{
                'align': []
            }],
            ['image', 'video'],
            ['clean']
        ];

        const quill = new Quill(id, {
            modules: {
                toolbar: toolbarOptions
            },
            theme: 'snow'
        });

        return quill;
    }
    
    function calculatePaymentTerm(createdDate, paymentTerm) {
        const data = [{
                id: '1',
                day: 0
            },
            {
                id: '2',
                day: 15
            },
            {
                id: '3',
                day: 20
            },
            {
                id: '4',
                day: 30
            },
            {
                id: '5',
                day: 45
            },
            {
                id: '6',
                day: null
            }
        ]
        let date = new Date(createdDate);
        paymentTerm = data.find(item => item.id == paymentTerm).day;
        if (paymentTerm == null) {
            return new Date(date.getFullYear(), date.getMonth() + 2, 0);
        }
        date.setDate(date.getDate() + paymentTerm);
        return date;
    }
</script>
