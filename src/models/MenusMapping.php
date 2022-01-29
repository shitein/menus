<?php

namespace Shitein\Menus\src\models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class MenusMapping extends Authenticatable
{
	 //Use Updater;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_master_id', 'menu_id', 'parent_id', 'reference_id', 'short_code', 'sequence', 'url', 'class', 'font_icon_class', 'is_deleted'];
    public $table       = 'menu_mapping';

    public function menus()
    {
        return $this->belongsTo(Menus::class);
    }
}
