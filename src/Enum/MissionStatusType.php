<?php

namespace App\Enum;

enum MissionStatusType: string
{
    case ON_GOING = 'on_going';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';
}
