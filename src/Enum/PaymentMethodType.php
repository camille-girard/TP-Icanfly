<?php

namespace App\Enum;

enum PaymentMethodType: string
{
    case CARD = 'card';
    case PAYPAL = 'paypal';
    case OTHER = 'other';
}
