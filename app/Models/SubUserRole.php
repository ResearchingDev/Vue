<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubUserRole extends Model
{
    use HasFactory;
    protected $table = 'sub_user_roles';
    protected $fillable = ['client_id','role_unique_code', 'role_name', 'status', 'web_access', 'mobile_access'];
    public function user_permission()
    {
        return $this->hasMany(UserPermission::class, 'role_id', 'id');
    }

}
