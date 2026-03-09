<?php

namespace App\Enums;

enum UserGiftEnum:string
{
    case AVAILABLE = "available";
    case CLAIMED = "claimed";
    case EXPIRED = "expired";
}
