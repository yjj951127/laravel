<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens;

    const CREATED_AT = 'create_time';

    const UPDATED_AT = 'update_time';

    public $timestamps = false;

    protected $table = 'user';

    protected $fillable = [
        'username', 'password', 'head_url','admin', 'create_time', 'is_delete', 'status', 'update_time'
    ];
}
