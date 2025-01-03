<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'sub_user_rights'; // Ensure this matches your DB table name
    protected $fillable = ['user_id', 'role_id', 'menu_id', 'can_delete', 'can_update', 'can_add', 'can_view'];
}
