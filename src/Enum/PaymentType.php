<?php

namespace App\Enum;

enum PaymentType : string
{
    public const CARD = 'card';
    public const CRYPTO = 'crypto';
    public const PAYPAL = 'paypal';
}


