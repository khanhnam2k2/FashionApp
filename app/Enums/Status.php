<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static OFF()
 * @method static ON()
 */
final class Status extends Enum
{
    const OFF = 0;
    const ON = 1;
}
