<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

#[Description('List možných statusů u toastify')]
final class ToastifyStatus extends Enum {
    const SUCCESS = 'success';

    const INFO = 'info';

    const ERROR = 'error';
}
