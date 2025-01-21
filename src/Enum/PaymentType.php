<?php

namespace App\Enum;

enum PaymentType: string
{
    case CARD = 'card';
    case CRYPTO = 'crypto';
    case PAYPAL = 'paypal';
}
