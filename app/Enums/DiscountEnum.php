<?php

namespace App\Enums;

enum DiscountEnum:string
{
    case ACTIVE = 'active';
    case USED = 'used';
    case EXPIRED = 'expired';
}
