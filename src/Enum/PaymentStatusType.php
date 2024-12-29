<?php

namespace App\Enum;

enum PaymentStatusType: string
{
    case PENDING = 'pending';

    case PAID = 'paid';

    case FAILED = 'failed';
}
