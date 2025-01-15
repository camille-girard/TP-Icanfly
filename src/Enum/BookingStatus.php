<?php

namespace App\Enum;

enum BookingStatus : string
{
    public const PENDING = 'pending';
    public const CONFIRMED = 'confirmed';
    public const CANCELLED = 'cancelled';
}
