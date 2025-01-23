<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case PENDING = 'en attente';
    case CONFIRMED = 'confirmée';
    case CANCELLED = 'supprimée';
}
