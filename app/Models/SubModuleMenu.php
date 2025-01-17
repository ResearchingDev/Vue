<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModuleMenu extends Model
{
    protected $table = 'sub_module_menus';
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function rights()
    {
        return $this->hasOne(UserPermission::class, 'menu_id', 'id');
    }
}
