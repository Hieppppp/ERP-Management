<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaymentTypeEnum extends Enum
{
    const CASH = 'cash';
    const CHECK = 'check';
    const DEBIT_CARD = 'debit';
    const VISA = 'visa';
    const MASTERCARD = 'master';
    const AMERICAN_EXPRESS = 'amex';
    const OTHER = 'other';

}
