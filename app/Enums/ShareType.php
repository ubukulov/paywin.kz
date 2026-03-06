<?php

namespace App\Enums;

enum ShareType: string
{
    case GIFT = 'gift';
    case DISCOUNT = 'discount';
    case CASHBACK = 'cashback';
    case PROMOCODE = 'promocode';
}
