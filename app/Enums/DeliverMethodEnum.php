<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DeliverMethodEnum extends Enum
{
    const SHIP = '1';
    const IN_STORE_PICKUP = '2';
}
