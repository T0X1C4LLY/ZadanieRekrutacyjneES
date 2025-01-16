<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\IsEnum;

enum PetStatus: string
{
    use isEnum;

    case AVAILABLE = 'available';
    case PENDING = 'pending';
    case SOLD = 'sold';
}
