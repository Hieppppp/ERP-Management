<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderTypeEnum extends Enum
{
    const PURCHASE = 'purchase_order';
    const SALE = 'sale_order';
    const RETURN = 'return';
}
