<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class InvoiceStatusEnum extends Enum
{
    const UNPAID = 'unpaid';
    const PAID = "paid";
    const PARTIALLY_PAID = "partially_paid";
}
