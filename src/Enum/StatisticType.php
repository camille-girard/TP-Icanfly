<?php

namespace App\Enum;

enum StatisticType: string
{
    case BOOKING = 'booking';

    case PAYMENT = 'payment';

    case MISSION = 'mission';
}
