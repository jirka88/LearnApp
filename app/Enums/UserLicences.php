<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserLicences extends Enum {
    const STANDART = 1;

    const STANDART_PLUS = 2;

    const PREMIUM = 3;
}
