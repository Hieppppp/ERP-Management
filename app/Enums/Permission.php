<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Permission extends Enum
{
    const WAREHOUSE = "warehouse";
    const UNIT = "unit";
    const TAX = "tax";
    const SHELVE = "shelve";
    const SUPPLIER = "supplier";
    const CATEGORY = "category";
    const PRODUCT = "product";
    const PURCHASE_ORDER = "purchase_order";
    const BATCH = "batch";
    const LOG = "log";
    const INVENTORY = "inventory";
    const CUSTOMER = "customer";
    const SALE_ORDER = "sale_order";
}
