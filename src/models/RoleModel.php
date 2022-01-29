<?php

namespace Shitein\Menus\models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class RoleModel extends Authenticatable
{
	 //Use Updater;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'role', 'redirect_url', 'cretaed_by', 'updated_by', 'created_at', 'updated_at'];
    public $table       = 'roles';
}
