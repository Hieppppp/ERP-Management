<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ActionLogEnum extends Enum
{
    const CREATED = 'created';
    const UPDATED = 'updated';
    const DELETED = 'deleted';
    const SENDED = 'sended';
    const RECEIVED = 'received';
    const CANCELED = 'canceled';
    const DONE = 'done';
    const CREATED_MANUALLY = 'created_manually';
    const MOVEMENT = 'movement';
}
