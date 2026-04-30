<?php

namespace App\Enums;

enum HttpStatus: int
{
    case OK = 200;
    case CREATED = 201;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case VALIDATION_ERROR = 422;
    case SERVER_ERROR = 500;
}
