<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PurchaseOrderStatusEnum extends Enum
{
    const CANCEL = 'cancel';
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const PENDING_SHELVE = "pending_shelve";
    const DONE = "done";
}
