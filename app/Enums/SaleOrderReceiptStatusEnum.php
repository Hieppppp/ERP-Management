<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SaleOrderReceiptStatusEnum extends Enum
{
    const READY = '1';
    const DONE = '2';
}
