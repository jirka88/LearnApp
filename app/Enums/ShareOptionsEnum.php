<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

#[Description('Stavy sdílení')]
final class ShareOptionsEnum extends Enum
{
    const DENIED = 0;
    const ALLOWED = 1;
}
