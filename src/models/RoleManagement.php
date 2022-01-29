<?php

namespace Shite\Menus\src\models;

use Illuminate\Database\Eloquent\Model;

class RoleManagement extends Model
{
    protected $fillable = ['id', 'company_id ', 'role_id', 'object_id','object_item_id', 'can_view',
                         'can_add ', 'can_edit','created_at','updated_at'];

    public $table       = 'role_management';

}
