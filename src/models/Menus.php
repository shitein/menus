<?php

namespace Shitein\Menus\models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class Menus extends Authenticatable
{
	 //Use Updater;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'short_code ', 'description', 'url','class', 'font_icon_class',
                         'language_key ', 'styles'];

    public $table       = 'menus';
    public function menumappings()
    {
        return $this->hasMany(MenusMapping::class,'menu_id', 'id');
    }
}
