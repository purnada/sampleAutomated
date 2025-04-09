<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    public static function getPermissions()
    {
        return [
            'view role',
            'create role',
            'edit role',
            'delete role',
            'view permission',
            'create permission',
            'edit permission',
            'delete permission',
            'view user',
            'create user',
            'edit user',
            'delete user',
            'view error log',
            'view language',
            'create language',
            'edit language',
            'view setting',
            'delete language',
            'view sector',
            'create sector',
            'edit sector',
            'delete sector',
            'view appointment',
            'create appointment',
            'edit appointment',
            'delete appointment',
            'approve appointment',
            'view doctor',
            'view province',
            'create province',
            'edit province',
            'delete province',
            'view district',
            'create district',
            'edit district',
            'delete district',
            'view municipality',
            'create municipality',
            'edit municipality',
            'delete municipality',
        ];
    }
}
