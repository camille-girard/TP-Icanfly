<?php

namespace App\Enum;

enum RoleUserType: string
{
    case ADMIN = 'admin';
    case OPERATOR = 'operator';
    case CLIENT = 'client';
}
