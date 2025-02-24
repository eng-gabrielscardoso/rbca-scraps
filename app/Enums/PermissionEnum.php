<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum PermissionEnum: string
{
    use EnumToArray;

    case CREATE_POST = 'create_post';
    case READ_POST = 'read_post';
    case UPDATE_POST = 'update_post';
    case DELETE_POST = 'delete_post';

    case CREATE_COMMENT = 'create_comment';
    case READ_COMMENT = 'read_comment';
    case UPDATE_COMMENT = 'update_comment';
    case DELETE_COMMENT = 'delete_comment';
}
