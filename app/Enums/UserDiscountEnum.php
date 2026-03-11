<?php

namespace App\Enums;

enum UserDiscountEnum:string
{
    case ACTIVE = 'active';
    case USED = 'used';
    case EXPIRED = 'expired';
}
