<?php

namespace App\Enum;

enum BookingStatus: string
{
    case PENDING = 'en attente';
    case CONFIRMED = 'confirmée';
    case CANCELLED = 'supprimée';
}
