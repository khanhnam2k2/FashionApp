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
    const cancelOrder = 0;
    const orderPlaced = 1;
    const confirmInformation = 2;
    const delivering = 3;
    const successfulDelivery = 4;
}
