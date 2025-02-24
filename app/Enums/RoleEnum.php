<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum RoleEnum: string
{
    use EnumToArray;

    case MODERATOR = 'moderator';
    case PUBLISHER = 'publisher';
}
