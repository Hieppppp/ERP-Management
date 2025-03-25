<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ReturnOrderStatusEnum extends Enum
{
    const READY = 'ready';
    const CANCEL = 'cancel';
    const DONE = 'done';
}
