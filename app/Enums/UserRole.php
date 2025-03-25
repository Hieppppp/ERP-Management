<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    const SUPPER_ADMIN = 'supper_admin';
    const ADMIN = 'admin';
    const USER = 'user';
}
