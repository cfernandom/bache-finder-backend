<?php

namespace App\Enums;

enum Roles: string
{
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';
    case INSTITUTION = 'institucion';
    case USER = 'usuario';
    case GUEST = 'invitado';
}