<?php

namespace App\Enums;

enum Category: string
{
    case DETAILS = 'detail';
    case ORDERS = 'order';
    case NEWS = 'news';
    case USERS = 'user';

}
