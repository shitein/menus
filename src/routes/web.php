<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Shitein\Menus\controllers', /*'prefix' => 'auth',*/ 'middleware' => ['auth','web']], function() {
    Route::resource('/menus', 'MenusController');
    Route::get('addMenuMappingItem', 'MenusController@addMenuMappingItem');
    // ** Edit Menu Route **//
    Route::get('editMenuMapping', 'MenusController@editMenuMapping');
    Route::post('/menus/update', 'MenusController@Update');
    Route::get('deleteMenuMappingAndRoles', 'MenusController@deleteMenuMappingAndRoles');

    Route::get('userAccessRole', 'MenusController@userAccessRole');
    Route::post('save-user-access-role', 'MenusController@save_user_role_access');
    Route::get('usermenuAccessTypes', 'MenusController@usermenuAccessTypes');
    // ** menus dragdrop **//
    Route::post('updateMenuList', 'MenusController@updateMenuList');
    // **   menus report  **/
    Route::get('menu_master_report', 'MenusReportController@index');
    /** * Export Excel */
    Route::get('export_to_excel', 'ExportController@export');
});
