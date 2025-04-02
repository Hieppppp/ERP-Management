let logTable;
const modelData = [
    {
        id: "App\\Models\\User",
        text: 'translation.menu.user'
    },
    {
        id: "App\\Models\\Category",
        text: 'translation.category.category'
    },
    {
        id: "App\\Models\\Shelve",
        text: 'translation.shelve.shelve'
    },
    {
        id: "App\\Models\\Supplier",
        text: 'translation.menu.supplier'
    },
    {
        id: "App\\Models\\Unit",
        text: 'translation.menu.unit'
    },
    {
        id: "App\\Models\\Warehouse",
        text: 'translation.menu.warehouse'
    },
    {
        id: "App\\Models\\Product",
        text: 'translation.menu.product'
    },
    {
        id: "App\\Models\\PurchaseOrder",
        text: 'translation.purchaseOrder.purchaseOrder'
    },
    {
        id: "App\\Models\\Customer",
        text: 'translation.menu.customer'
    },
    {
        id: "App\\Models\\Department",
        text: 'translation.menu.department'
    },
    {
        id: "App\\Models\\Position",
        text: 'translation.menu.position'
    },
    {
        id: "App\\Models\\Employee",
        text: 'translation.menu.employee'
    },
];
$(function (e) {
    logTable = $("#log-datatable").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        processing: true,
        serverSide: true,
        responsive: false,
        search: {
            return: true,
        },
        order: [[0, "desc"]],
        language: datatableLanguage(),
        ajax: {
            url: "/api/v1/log",
            type: "GET",
            error: function () {
                notification('error', trans(
                    "message.there_was_an_error_trying_to_get_list_please_try_again_later"
                ));
            },
        },
        columns: [
            {
                data: "code",
                name: "id",
            },
            {
                data: "created_at",
                name: "created_at",
            },
            {
                data: function (data) {
                    let module = modelData.find(item => item.id === data.module);
                    if (module) {
                        return trans(module.text);
                    }
                    return '';
                },
                name: "module",
            },
            {
                data: "user",
                name: "user",
            },
            {
                data: function (data) {
                    return data.action.charAt(0).toUpperCase() + data.action.slice(1)
                },
                name: "action",
            },
            {
                data: function (data) {
                    return renderDescription(data);
                },
                name: "description",
            }
        ],
        columnDefs: [
            { targets: 'no-sort', sortable: false, orderable: false },
            {
                className: 'max-width-address',
                targets: 5
            },
        ],
        initComplete: function (settings) {
            let moduleData = modelData.map(item => ({ id: item.id, text: trans(item.text) }));
            let actionData = [
                {
                    id: 'created',
                    text: trans('translation.actionLog.created')
                },
                {
                    id: 'updated',
                    text: trans('translation.actionLog.updated')
                },
                {
                    id: 'deleted',
                    text: trans('translation.actionLog.deleted')
                },
                {
                    id: 'sended',
                    text: trans('translation.actionLog.sent')
                },
                {
                    id: 'received',
                    text: trans('translation.actionLog.received')
                },
                {
                    id: 'canceled',
                    text: trans('translation.actionLog.cancelled')
                },
                {
                    id: 'done',
                    text: trans('translation.actionLog.done')
                },
            ]
            setDatatableFilters({
                'api': this.api(),
                'tableId': settings.sTableId,
                'select': {
                    'module': {
                        'dataType': 'fix',
                        'data': moduleData,
                        'placeholder': trans('translation.placeHolder.selectModule')
                    },
                    'action': {
                        'dataType': 'fix',
                        'data': actionData,
                        'placeholder': trans('translation.placeHolder.selectAction')
                    }
                }
            })
        },
    });

    $("#log-datatable_filter input").on("input", function () {
        if (!$(this).val()) {
            logTable.search("").draw();
        }
    });
});

function renderModule (model) {
    return model.module.name;
}

function renderDescription (data) {
    let module = modelData.find(item => item.id === data.module);
    let code = `#${data.subject_id}`;
    const action = data.action.charAt(0).toUpperCase() + data.action.slice(1);

    if (module) {
        module = trans(module.text);
    }
    switch (data.module) {
        case 'App\\Models\\PurchaseOrder':
            code = data?.subject?.code ?? `P${data.subject_id.toString().padStart(5, '0')}`;
            break;
        case 'App\\Models\\Shelve':
            code = data?.subject?.code ?? `WH${data?.properties?.warehouse_id}-S${data.subject_id}`;
            break;
        case 'App\\Models\\Warehouse':
            code = data?.subject?.code ?? `WH${data.subject_id}`;
            break;
    }

    const description = data.action === 'updated' ? renderUpdateDescription(data) : [];

    const title = `${module} ${code} ${action}`;
    return description.length > 0 ? `${title}\n<ul>${description.join(' ')}</ul>` : title;
}

function renderUpdateDescription (data) {
    const renderMap = {
        'App\\Models\\Shelve': renderDescriptionByModel,
        'App\\Models\\User': renderDescriptionByModel,
        'App\\Models\\Unit': renderDescriptionByModel,
        'App\\Models\\Warehouse': renderDescriptionByModel,
        'App\\Models\\Category': renderCategory,
        'App\\Models\\Department': renderCategory,
        'App\\Models\\Position': renderCategory,
        'App\\Models\\Supplier': renderDescriptionByModel,
        'App\\Models\\Product': renderProductLog,
        'App\\Models\\Customer': renderDescriptionByModel,
        'App\\Models\\Employee': renderDescriptionByModel,
        'default': renderDescriptionProduct
    };

    const renderFunc = renderMap[data.module] || renderMap['default'];
    return renderFunc(data?.properties?.attributes, data?.properties?.old, data.module);
}

function renderDescriptionByModel (attributes, old, module) {
    const transMap = {
        'App\\Models\\Shelve': [
            { key: 'warehouse_id', trans: 'translation.shelve.warehouse', prefix: 'WH' },
            { key: 'name', trans: 'translation.shelve.name' },
            { key: 'location', trans: 'translation.shelve.location' }
        ],
        'App\\Models\\User': [
            { key: 'username', trans: 'translation.user.userName' },
            { key: 'first_name', trans: 'translation.user.firstName' },
            { key: 'last_name', trans: 'translation.user.lastName' },
            { key: 'role', trans: 'translation.user.role' },
            { key: 'avatar', trans: 'translation.user.userImage', msgKey: 'message.updated' },
            { key: 'email', trans: 'translation.user.email' },
            { key: 'password', trans: 'translation.user.password', msgKey: 'message.updated' },
            { key: 'permission_ids', trans: 'translation.role.rolePermission', msgKey: 'message.updated' }
        ],
        'App\\Models\\Unit': [
            { key: 'name', trans: 'translation.unit.name' },
            { key: 'symbol', trans: 'translation.unit.symbol' },
            { key: 'description', trans: 'translation.unit.description' }
        ],
        'App\\Models\\Warehouse': [
            { key: 'name', trans: 'translation.warehouse.name' },
            { key: 'detail_address', trans: 'translation.warehouse.address' },
            { key: 'city', trans: 'translation.warehouse.city' },
            { key: 'province', trans: 'translation.warehouse.province' },
            { key: 'country', trans: 'translation.warehouse.country' },
            { key: 'postal_code', trans: 'translation.warehouse.postalCode' }
        ],
        'App\\Models\\Supplier': [
            { key: 'name', trans: 'translation.supplier.name' },
            { key: 'logo', trans: 'translation.supplier.logo', msgKey: 'message.updated' },
            { key: 'email', trans: 'translation.supplier.email' },
            { key: 'phone', trans: 'translation.supplier.phoneNumber' },
            { key: 'detail_address', trans: 'translation.supplier.address' },
            { key: 'country', trans: 'translation.supplier.country' },
            { key: 'province', trans: 'translation.supplier.province' },
            { key: 'city', trans: 'translation.supplier.city' },
            { key: 'site', trans: 'translation.supplier.site' }
        ],
        'App\\Models\\Customer' : [
            { key: 'first_name', trans: 'translation.customer.firstName'},
            { key: 'last_name', trans: 'translation.customer.lastName'},
            { key: 'email', trans: 'translation.customer.email'},
            { key: 'phone', trans: 'translation.customer.phoneNumber'},
            { key: 'detail_address', trans: 'translation.customer.address'},
            { key: 'country', trans: 'translation.customer.country' },
            { key: 'province', trans: 'translation.customer.province' },
            { key: 'city', trans: 'translation.customer.city' },
            { key: 'avatar', trans: 'translation.customer.avatar', msgKey: 'message.updated'},
        ],
        'App\\Models\\Employee': [
            { key: 'name', trans: 'translation.employee.name' },
            { key: 'detail_address', trans: 'translation.employee.address' },
            { key: 'city', trans: 'translation.employee.city' },
            { key: 'province', trans: 'translation.employee.province' },
            { key: 'country', trans: 'translation.employee.country' },
            { key: 'postal_code', trans: 'translation.employee.postalCode' }
        ],
    };

    return renderDescriptionItems(attributes, old, transMap[module]);
}

function renderCategory (attributes, old) {
    return renderDescriptionItems(attributes, old, [
        { key: 'name', trans: 'translation.unit.name' },
        { key: 'description', trans: 'translation.unit.description' }
    ]);
}

function renderDescriptionItems (attributes, old, transArray) {
    const data = [];
    if (attributes && old) {
        for (const key in old) {
            if (old.hasOwnProperty(key) && old[key] && attributes[key] !== undefined) {
                const transItem = transArray.find(item => item.key === key);
                if (transItem) {
                    const translatedKey = trans(transItem.trans);
                    const value = transItem.prefix ? `${transItem.prefix}${old[key]}` : old[key];
                    const newValue = transItem.prefix ? `${transItem.prefix}${attributes[key]}` : attributes[key];
                    const listItem = transItem.msgKey ? `<li>${trans(transItem.msgKey, { field: translatedKey })}</li>` : `<li>${translatedKey} : ${value} <i class="fa fa-long-arrow-right"></i> ${newValue}</li>`;
                    data.push(listItem);
                }
            }
        }
    }
    return data;
}

function renderDescriptionProduct (attributes, old) {
    const logItems = [];
    if (old.supplier_id != attributes.supplier_id) {
        logItems.push(`<li>${old.supplier.name} <i class="fa fa-long-arrow-right"></i> ${attributes.supplier.name}</li>`);
    }

    if (old.warehouse_id != attributes.warehouse_id) {
        const oldWarehouse = [old.warehouse.detail_address, old.warehouse.city, old.warehouse.province, old.warehouse.country].join(', ');
        const newWarehouse = [attributes.warehouse.detail_address, attributes.warehouse.city, attributes.warehouse.province, attributes.warehouse.country].join(', ');
        logItems.push(`<li>${oldWarehouse} <i class="fa fa-long-arrow-right"></i> ${newWarehouse}</li>`);
    }

    if (new Date(old.scheduled_date.replace(' 00:00:00', '')).toISOString().split('T')[0] !== new Date(attributes.scheduled_date).toISOString().split('T')[0]) {
        const oldDate = new Date(old.scheduled_date.replace(' 00:00:00', '')).toISOString().split('T')[0];
        const newDate = new Date(attributes.scheduled_date).toISOString().split('T')[0];
        logItems.push(`<li>${oldDate} <i class="fa fa-long-arrow-right"></i> ${newDate}</li>`);
    }

    const productDifference = getItemDifference(old.products, attributes.products, ['pivot.quantity']);

    if (productDifference.create.length > 0) {
        productDifference.create.forEach(product => logItems.push(`<li>${trans('message.createProduct', { product: product.name })}</li>`));
    }

    if (productDifference.delete.length > 0) {
        productDifference.delete.forEach(product => logItems.push(`<li>${trans('message.deleteProduct', { product: product.name })}</li>`));
    }

    if (productDifference.update.length > 0) {
        productDifference.update.forEach(product => logItems.push(`<li>${product.name} : ${product.old_pivot_quantity} <i class="fa fa-long-arrow-right"></i> ${product.new_pivot_quantity}</li>`));
    }

    return logItems;
}

function getItemDifference (oldItems, newItems, compareKeys = []) {
    const result = {
        update: [],
        create: [],
        delete: [],
    };

    const oldItemMap = new Map(oldItems.map(item => [item.id, item]));
    const newItemMap = new Map(newItems.map(item => [item.id, item]));

    oldItemMap.forEach((oldItem, id) => {
        if (newItemMap.has(id) && compareKeys.length) {
            const newItem = newItemMap.get(id);
            let hasChanges = false;
            let updates = { id: id, name: oldItem.name };

            compareKeys.forEach(key => {
                if (key.includes('.')) {
                    const [mainKey, subKey] = key.split('.');
                    if (oldItem[mainKey] && newItem[mainKey] && oldItem[mainKey][subKey] !== newItem[mainKey][subKey]) {
                        hasChanges = true;
                        updates[`old_${mainKey}_${subKey}`] = oldItem[mainKey][subKey];
                        updates[`new_${mainKey}_${subKey}`] = newItem[mainKey][subKey];
                    }
                } else {
                    if (oldItem[key] !== newItem[key]) {
                        hasChanges = true;
                        updates[`old_${key}`] = oldItem[key];
                        updates[`new_${key}`] = newItem[key];
                    }
                }
            });

            if (hasChanges) {
                result.update.push(updates);
            }
        } else {
            result.delete.push(oldItem);
        }
    });

    newItemMap.forEach((newItem, id) => {
        if (!oldItemMap.has(id)) {
            result.create.push(newItem);
        }
    });

    return result;
}

function renderProductLog (attributes, old) {
    const logItems = [];
    if (old.name != attributes.name) {
        logItems.push(`<li>${trans('translation.product.name')}: ${old.name} <i class="fa fa-long-arrow-right"></i> ${attributes.name}</li>`);
    }

    if (old.unit_id != attributes.unit_id) {
        logItems.push(`<li>${trans('translation.product.unit')}: ${old.unit_name} <i class="fa fa-long-arrow-right"></i> ${attributes.unit_name}</li>`);
    }

    if (old.min_quantity != attributes.min_quantity) {
        logItems.push(`<li>${trans('translation.product.minQuantity')}: ${old.min_quantity} <i class="fa fa-long-arrow-right"></i> ${attributes.min_quantity}</li>`);
    }

    if (old.max_quantity != attributes.max_quantity) {
        logItems.push(`<li>${trans('translation.product.maxQuantity')}: ${old.max_quantity} <i class="fa fa-long-arrow-right"></i> ${attributes.max_quantity}</li>`);
    }

    if (old.category_id != attributes.category_id) {
        logItems.push(`<li>${trans('translation.product.category')}: ${old.category_name} <i class="fa fa-long-arrow-right"></i> ${attributes.category_name}</li>`);
    }

    if (old.unit_price != attributes.unit_price) {
        logItems.push(`<li>${trans('translation.product.unitPrice')}: ${old.unit_price} <i class="fa fa-long-arrow-right"></i> ${attributes.unit_price}</li>`);
    }

    if (old.description != attributes.description) {
        logItems.push(`<li>${trans('translation.product.descriptionUpdated')}</li>`);
    }

    const parentProductDifference = getItemDifference(old.parent_products, attributes.parent_products);

    if (parentProductDifference.create.length) {
        parentProductDifference.create.forEach(product => logItems.push(`<li>${trans('translation.product.parentProduct')}: ${trans('message.createProduct', { product: product.name })}</li>`));
    }

    if (parentProductDifference.delete.length) {
        parentProductDifference.delete.forEach(product => logItems.push(`<li>${trans('translation.product.parentProduct')}: ${trans('message.deleteProduct', { product: product.name })}</li>`));
    }

    const supplierDifference = getItemDifference(old.suppliers, attributes.suppliers, ['pivot.unit_cost']);
    if (supplierDifference.create.length) {
        supplierDifference.create.forEach(supplier => logItems.push(`<li>${trans('translation.product.supplier')}: ${trans('message.createProduct', { product: supplier.name })}</li>`));
    }

    if (supplierDifference.delete.length) {
        supplierDifference.delete.forEach(supplier => logItems.push(`<li>${trans('translation.product.supplier')}: ${trans('message.deleteProduct', { product: supplier.name })}</li>`));
    }

    if (supplierDifference.update.length > 0) {
        supplierDifference.update.forEach(supplier => logItems.push(`<li>${trans('translation.product.supplier')} - ${supplier.name} : ${supplier.old_pivot_unit_cost} <i class="fa fa-long-arrow-right"></i> ${supplier.new_pivot_unit_cost}</li>`));
    }

    return logItems;
}
