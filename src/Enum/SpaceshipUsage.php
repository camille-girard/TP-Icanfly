<?php

namespace App\Enum;

enum SpaceshipUsage: string
{
    case CARGO = 'cargo';
    case TRAVEL = 'touristique';
    case SCIENTIFIC = 'scientifique';
}
