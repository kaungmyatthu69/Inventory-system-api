<?php

namespace App\Enums;

enum OrderStatus: string
{
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
