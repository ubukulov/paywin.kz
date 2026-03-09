<?php

namespace App\Enums;

enum PayoutEnum:string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
}
