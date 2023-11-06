<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Status order
 * @method static static cancelOrder()
 * @method static static orderPlaced()
 * @method static static confirmInformation()
 * @method static static delivering()
 * @method static static successfulDelivery()
 */
final class StatusOrder extends Enum
{
    const cancelOrder = 0;
    const orderPlaced = 1;
    const confirmInformation = 2;
    const delivering = 3;
    const successfulDelivery = 4;
}
