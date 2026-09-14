<?php
declare(strict_types=1);
namespace App\Enums;
use BenSampo\Enum\Enum;
final class MaterialRequestStatusEnum extends Enum
{
    const DRAFT = 'draft'; const PENDING = 'pending'; const APPROVED = 'approved';
    const REJECTED = 'rejected'; const PARTIALLY_ISSUED = 'partially_issued';
    const ISSUED = 'issued'; const CANCELLED = 'cancelled';
}
