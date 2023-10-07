<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * User role (ADMIN || USER)
 * @method static ADMIN
 * @method static USER
 */
final class UserRole extends Enum
{
    const ADMIN = 0;
    const USER = 1;
}
