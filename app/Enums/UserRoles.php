<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

#[Description('List of available user roles')]
final class UserRoles extends Enum {
    const ADMIN = 1;

    const OPERATOR = 2;

    const TESTER = 3;

    const BASIC_USER = 4;
}
