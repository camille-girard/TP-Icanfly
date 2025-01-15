<?php

namespace App\Enum;

enum PaymentStatus : string
{
    public const PENDING = 'pending';
    public const CONFIRMED = 'confirmed';
    public const CANCELLED = 'cancelled';
}
