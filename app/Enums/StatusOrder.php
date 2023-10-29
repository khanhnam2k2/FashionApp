<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StatusOrder extends Enum
{
    const orderPlaced = 0;
    const confirmInformation = 1;
    const delivering = 2;
    const successfulDelivery = 3;
}
