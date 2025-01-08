<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModuleMenu extends Model
{
    protected $table = 'sub_module_menus';
    public function rights()
    {
        return $this->hasOne(UserPermission::class, 'menu_id', 'id');
    }
}
