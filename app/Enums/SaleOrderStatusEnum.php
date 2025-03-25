<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SaleOrderStatusEnum extends Enum
{
    const DRAFT = '1';
    const CONFIRM = '2';
    const IN_TRANSIT = '3';
    const DELIVERED = '4';
    const CANCEL = '5';
}
