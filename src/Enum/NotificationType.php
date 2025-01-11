<?php

namespace App\Enum;

enum NotificationType: string
{
    case MISSION = 'mission';
    case PAYMENT = 'payment';
    case OTHER = 'other';
}
