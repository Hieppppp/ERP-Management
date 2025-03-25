<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaymentTermTypeEnum extends Enum
{
    const IMMEDIATE_PAYMENT = 1;
    const FIFTEEN_DAYS = 2;
    const TWENTY_DAYS = 3;
    const THIRTY_DAYS = 4;
    const FORTY_FIVE_DAYS = 5;
    const END_OF_FOLLOWING_MONTH = 6;

}
