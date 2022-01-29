<?php

namespace Shitein\Menus\controllers;

use App\Common\Common;
use App\Package\Menus\src\controllers\Base\MenusBaseController;
use App\Package\Menus\src\controllers\Controller_services\MenusServiceController;
use App\Package\Menus\src\controllers\Base\ErrorlogServiceController;
use Shitein\Menus\models\MasterModel;
use Shitein\Menus\models\Menus;
use Shitein\Menus\models\MenusMapping;
use Illuminate\Http\Request;
use App\Package\Menus\src\models\RoleModel;
use App\Http\Requests;
use Exception;
//use Illuminate\Support\Facades\Validator;

class MenusController extends Controller
{

    /**
     * Display Menu Listing.
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $requestData    = $request->all();
        //dd($requestData); exit;
        $masterModel    = new MasterModel();
        $stringMapping  = '';
        $menuList       = '';
        $obj = new Common();

        try {

            $select     = ['id', 'code', 'description', 'master_code', 'description'];
            $where       = ['master_code' => 'menu', 'is_deleted' => '0'];
            $likeType   = 'or';
            $orderBy    = ['sequence' => 'desc'];

            if (isset($requestData['menu_name']))
            {
                $where['id']    = $requestData['menu_name'];
                $menusMaster = $masterModel ->getMasterRow('general_master', $where);
                //dd($menusMaster );exit;
                $stringMapping = '<input type="hidden" name="menu_name" id="menu_name" value="' . $requestData['menu_name'] . '"';
                if (!empty($menusMaster)) {
                    $where       = ['master_code' => 'menu', 'is_deleted' => '0'];
                    $likeType   = 'or';
                    $menusMasterList = $masterModel ->getMaster('general_master', false, $where, false, $likeType, $orderBy);

                    $menuList = MenusBaseController::getMenuList();

                    $parentList = MenusBaseController::getMenuById($requestData['menu_name']);

                    if (!empty($parentList)) {
                        foreach ($parentList as $parentMenuDetail) {
                            $childList = MenusBaseController::getMenuById($requestData['menu_name'], $parentMenuDetail->id);

                            if ($parentMenuDetail->is_deleted == 1) {
                                $dangerClass = 'text-danger';
                            } else {
                                $dangerClass = '';
                            }

                            $stringMapping .=  '<li class="dd-item 111" data-id="' . $parentMenuDetail->id . '" data-menuid="' . $parentMenuDetail->menu_id . '" data-url="' . $parentMenuDetail->url . '" data-shortcode="' . $parentMenuDetail->short_code . '" data-name="' . $obj->get_translation($parentMenuDetail->language_key, $parentMenuDetail->description) . '" data-new="0" data-deleted="0">
                                                <div class="dd-handle ' . $dangerClass . '">' . $obj->get_translation($parentMenuDetail->language_key, $parentMenuDetail->description) . '</div>
                                                <div class="all-icon">
                                                    <input type="hidden" name="menu[]" value="' . $parentMenuDetail->id . '">
                                                    <span class="button-delete pull-right" data-owner-id="' . $parentMenuDetail->id . '" data-owner-name="' . $obj->get_translation($parentMenuDetail->language_key, $parentMenuDetail->description) . '">
                                                      <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="button-edit pull-right" data-menu-url="' . $requestData['menu_name'] . '"  data-font_icon_class="' . $parentMenuDetail->font_icon_class . '" data-class="' . $parentMenuDetail->class . '" data-url="' . $parentMenuDetail->url . '" data-shortcode="' . $parentMenuDetail->short_code . '" data-owner-id="' . $parentMenuDetail->menu_id . '"  data-owner-name="' . $obj->get_translation($parentMenuDetail->language_key, $parentMenuDetail->description) . '" data-language-key="' . $parentMenuDetail->language_key . '" data-styles="'. $parentMenuDetail->styles . '" data-toggle="modal" data-target="#editMenuModal">
                                                       <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="user-menu-access-control pull-right" data-heading="' . $obj->get_translation($parentMenuDetail->language_key, $parentMenuDetail->description) . '" data-mappingid="' . $parentMenuDetail->id . '" data-parentid="' . $parentMenuDetail->parent_id . '" data-menuid="' . $parentMenuDetail->menu_id . '" data-url="' . $parentMenuDetail->url . '">
                                                        <i class="fa " aria-hidden="true"></i>
                                                    </span>
                                                </div>';

                            if ($childList->isEmpty()) {
                                $stringMapping .= '</li>';
                            } else {
                                $stringMapping .= '<ol class="dd-list">';
                                foreach ($childList as $childDetail) {
                                    $subChildList = MenusBaseController::getMenuById($requestData['menu_name'], $childDetail->id);

                                    if ($childDetail->is_deleted == 1) {
                                        $dangerClass = 'text-danger';
                                    } else {
                                        $dangerClass = '';
                                    }

                                    $stringMapping .= '<li class="dd-item 222" data-id="' . $childDetail->id . '" data-menuid="' . $childDetail->menu_id . '" data-url="' . $childDetail->url . '" data-shortcode="' . $childDetail->short_code . '" data-name="' . $obj->get_translation($childDetail->language_key, $childDetail->description) . '" data-slug="' . $childDetail->url . '" data-new="0" data-deleted="0">
                                                        <div class="dd-handle ' . $dangerClass . '">' . $obj->get_translation($childDetail->language_key, $childDetail->description) . '</div>
                                                        <div class="all-icon">
                                                            <input type="hidden" name="menu[]" value="' . $childDetail->id . '">
                                                            <span class="button-delete pull-right" data-owner-id="' . $childDetail->id . '" data-owner-name="' . $obj->get_translation($childDetail->language_key, $childDetail->description) . '">
                                                                <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                            </span>
                                                            <span class="button-edit pull-right" data-menu-url="' . $requestData['menu_name'] . '"  data-font_icon_class="' . $childDetail->font_icon_class . '"  data-class="' . $childDetail->class . '"  data-url="' . $childDetail->url . '" data-shortcode="' . $childDetail->short_code . '" data-owner-id="' . $childDetail->menu_id . '" data-owner-name="' . $obj->get_translation($childDetail->language_key, $childDetail->description) . '" data-language-key="' . $childDetail->language_key . '" data-styles="'. $childDetail->styles . '" data-toggle="modal" data-target="#editMenuModal">
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                            </span>
                                                            <span class="user-menu-access-control pull-right" data-heading="' . $childDetail->text . '" data-mappingid="' . $childDetail->id . '" data-parentid="' . $childDetail->parent_id . '" data-menuid="' . $childDetail->menu_id . '" data-url="' . $childDetail->url . '">
                                                                <i class="fa " aria-hidden="true"></i>
                                                            </span>
                                                        </div>';

                                    if ($subChildList->isEmpty()) {
                                        $stringMapping .= '</li>';
                                    } else {
                                        $stringMapping .= '<ol class="dd-list">';
                                        foreach ($subChildList as $subChildDetail) {
                                            $stringMapping .= '<li class="dd-item 333" data-id="' . $subChildDetail->id . '" data-menuid="' . $subChildDetail->menu_id . '" data-url="' . $subChildDetail->url . '" data-shortcode="' . $subChildDetail->short_code . '" data-name="' . $obj->get_translation($subChildDetail->language_key, $subChildDetail->description) . '" data-slug="' . $subChildDetail->url . '" data-new="0" data-deleted="0">
                                                                <div class="dd-handle">' . $obj->get_translation($subChildDetail->language_key, $subChildDetail->description) . '</div>
                                                                <div class="all-icon">
                                                                    <input type="hidden" name="menu[]" value="' . $subChildDetail->id . '">
                                                                    <span class="button-delete pull-right" data-owner-id="' . $subChildDetail->id . '" data-owner-name="' . $obj->get_translation($subChildDetail->language_key, $subChildDetail->description) . '">
                                                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                                    </span>
                                                                    <span class="button-edit pull-right"  data-menu-url="' . $requestData['menu_name'] . '"  data-font_icon_class="' . $subChildDetail->font_icon_class . '"  data-class="' . $subChildDetail->class . '"  data-url="' . $subChildDetail->url . '" data-shortcode="' . $subChildDetail->short_code . '" data-owner-id="' . $subChildDetail->menu_id . '" data-owner-name="' . $obj->get_translation($subChildDetail->language_key, $subChildDetail->description) . '" data-language-key="' . $subChildDetail->language_key . '" data-styles="'. $subChildDetail->styles . '" data-toggle="modal" data-target="#editMenuModal">
                                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                    </span>
                                                                    <span class="user-menu-access-control pull-right" data-heading="' . $subChildDetail->text . '" data-mappingid="' . $subChildDetail->id . '" data-parentid="' . $subChildDetail->parent_id . '" data-menuid="' . $subChildDetail->menu_id . '" data-url="' . $subChildDetail->url . '">
                                                                        <i class="fa " aria-hidden="true"></i>
                                                                    </span>
                                                                </div>';
                                        }
                                        $stringMapping .= '</ol></li>';
                                    }
                                }
                                $stringMapping .= '</ol></li>';
                            }
                        }
                        // $stringMapping .= "<div class='col-md-12 mt-2 mb-2 text-right access-controls display-none'><input type='submit' id='save-access-type' class='btn btn-primary'></div>";
                    }
                }
            } else {
                $menusMasterList =$masterModel->getMaster('general_master', $select, $where, false, $likeType, $orderBy);
                //dd($menusMasterList);
                $menuMappingList = MenusMapping::all('menu_id');

                $menuList = Menus::whereNotIn('id', $menuMappingList)->get();
                //dd($menuList);
            }

            return view('Menus::super_admin.menus.index', compact('menusMasterList', 'stringMapping', 'menuList'));
        } catch (Exception $ex) {

            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'index', 'MenusController.php');
            // return view('errors.oh!');

            dd($ex->getMessage());
            dd($ex->getLine());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        try {
            $validate =\Validator::make($requestData,[
                'short_code'=> ['required','min:3','unique:menus,short_code,' . $request->short_code],
                'description' => 'required'
            ]);

            if($validate->passes()){
                MenusServiceController::store_menus($requestData);
            }else{
                return response()->json(['status' => false, 'error' => $validate->errors()->all()]);
            }
            return redirect('menus');

        } catch (Exception $ex) {
            dd($ex->getMessage());
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'index', 'MenusController.php');
            // return view('errors.oh!');

        }
    }


    public function update(Request $request)
    {
        $requestData = $request->all();
        //dd($requestData);
        try {
           MenusServiceController::update_menus($requestData);
            //dd($abc);//1
           return redirect('menus?menu_name=' . $requestData['menu_url']);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'update', 'MenusController.php');
            // return view('errors.oh!');
        }
    }


     /*  @ Delete parent & there menu mapping     */

    public function deleteMenuMappingAndRoles(Request $request)
    {

        try {
            $requestData = $request->all();
            $response = MenusServiceController::delete_menu_mappings($requestData);
            return $response;
        } catch (Exception $ex) {
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'deleteMenuMappingAndRoles', 'MenusController.php');
            // return view('errors.oh!');
        }
    }

   /* @ Update Menu on drag-drop event. */

    public function updateMenuList(Request $request)
    {
       // dd($request);
        try {
            $response = MenusServiceController::update_menu_list($request);
            //dd( $response);
            return $response;
        } catch (Exception $ex) {
            echo ($ex->getMessage());
            dd($ex->getLine());
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'updateMenuList', 'MenusController.php');
            // return view('errors.oh!');
        }
    }



    public function MenuList(Request $request)
    {
        $requestData = $request->all();
        $menus   = new Menus();
        $childMenu = '';
        $subChildMenu = '';

        try {
            $where['short_code']    = $requestData['menu_name'];
            $parentMenu = $menus->getMasterRow('menus', $where);

            if (!empty($parentMenu)) {

                $where1['parent_id'] = $parentMenu->id;
                $childMenu    = $menus->getMaster('menu_mapping', false, $where1, false, false, false);

                if (!empty($childMenu)) {
                    foreach ($childMenu as $childMenuDetails) {

                        $where1['parent_id'] = $childMenuDetails->id;
                        $subChildMenu = $menus->getMaster('menu_mapping', false, $where1, false, false, false);
                    }
                }
            }

            return response(array(
                'error' => false,
                'data'  => array('ParentMenu' => $parentMenu, 'ChildMenu' => $childMenu, 'subChildMenu' => $subChildMenu)
            ), 200);
        } catch (Exception $ex) {
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'MenuList', 'MenusController.php');
            // return view('errors.oh!');
        }
    }

    /*
     @ Function to add menu in menu mapping.
     */

    public function addMenuMappingItem(Request $request)
    {
        $requestData = $request->all();
       // dd($requestData);
        try {
            $response = MenusServiceController::save_menu_mappings($requestData);
            return $response;
        } catch (Exception $ex) {
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'addMenuMappingItem', 'MenusController.php');
            // return view('errors.oh!');
           echo $ex->getLine();
        }
    }





    public function usermenuAccessTypes(Request $request)
    {
        try {
            $requestData = $request->all();
            $stringMapping = '<input type="hidden" name="menu_name" id="menu_name" value="' . $requestData['menu_name'] . '">';

            $parentList = MenusBaseController::getMenuById($requestData['menu_name']);
            if (isset($requestData['role'])) {
                $role = $requestData['role'];
            } else {
                $rolesList = RoleModel::whereIn('company_id', [$requestData['company']])->get();
                $role = $rolesList[0]->id;
            }
            $roleManagement = MenusBaseController::getUserRoleDetails($role);
            if (!empty($parentList)) {
                $stringMapping .= '<table style="font-size: 12px; width: 100%;margin-top: -16px;"><tbody>';
                foreach ($parentList as $parentMenuDetail) {

                    $childList = MenusBaseController::getMenuById($requestData['menu_name'], $parentMenuDetail->id);

                    if (!empty($roleManagement)) {
                        $canview = $canadd = $canedit = '';
                        foreach ($roleManagement as $access) {
                            if ($parentMenuDetail->id == $access->object_id) {
                                if ($access->can_view == 1) {
                                    $canview = 'checked';
                                }
                                if ($access->can_add == 1) {
                                    $canadd = 'checked';
                                }
                                if ($access->can_edit == 1) {
                                    $canedit = 'checked';
                                }
                                break;
                            }
                        }
                    }
                    $stringMapping .= '<tr><td width="15%"><span class="access-dd-handle user-menu-view-control access-controls">
                                        <input type="checkbox" class="viewmenu" name="menu_view' . $parentMenuDetail->id . '" ' . $canview . '>
                                        </span></td>
                                        <td width="15%"><span class="access-dd-handle user-menu-add-control access-controls">
                                            <input type="checkbox" class="addmenu" name="menu_add' . $parentMenuDetail->id . '" ' . $canadd . '>
                                        </span></td>
                                        <td width="15%"><span class="access-dd-handle user-menu-edit-control access-controls">
                                            <input type="checkbox" class="editmenu" name="menu_edit' . $parentMenuDetail->id . '" ' . $canedit . '>
                                        </span></td><td width="55%"><input type="hidden" name="menu[]" id="menus" value="' . $parentMenuDetail->id . '"></td></tr>';

                    if (!empty($childList)) {
                        foreach ($childList as $childDetail) {
                            $subChildList = MenusBaseController::getMenuById($requestData['menu_name'], $childDetail->id);
                            if (!empty($roleManagement)) {
                                $canview = $canadd = $canedit = '';
                                foreach ($roleManagement as $access) {
                                    if ($childDetail->id == $access->object_id) {
                                        if ($access->can_view == 1) {
                                            $canview = 'checked';
                                        }
                                        if ($access->can_add == 1) {
                                            $canadd = 'checked';
                                        }
                                        if ($access->can_edit == 1) {
                                            $canedit = 'checked';
                                        }
                                        break;
                                    }
                                }
                            }
                            $stringMapping .= '<tr><td width="15%"><span class="access-dd-handle user-menu-view-control access-controls">
                                                <input type="checkbox" class="viewmenu" name="menu_view' . $childDetail->id . '" ' . $canview . '>
                                            </span></td>
                                            <td width="15%"><span class="access-dd-handle user-menu-add-control access-controls">
                                                <input type="checkbox" class="addmenu" name="menu_add' . $childDetail->id . '" ' . $canadd . '>
                                            </span></td>
                                            <td width="15%"><span class="access-dd-handle user-menu-edit-control access-controls">
                                                <input type="checkbox" class="editmenu" name="menu_edit' . $childDetail->id . '" ' . $canedit . '>
                                            </span></td><td width="55%"><input type="hidden" name="menu[]" id="menus" value="' . $childDetail->id . '"></td></tr>';

                            if (!empty($subChildList)) {
                                foreach ($subChildList as $subChildDetail)
                                 {
                                    if (!empty($roleManagement)) {
                                        $canview = $canadd = $canedit = '';
                                        foreach ($roleManagement as $access) {
                                            if ($subChildDetail->id == $access->object_id) {
                                                if ($access->can_view == 1) {
                                                    $canview = 'checked';
                                                }
                                                if ($access->can_add == 1) {
                                                    $canadd = 'checked';
                                                }
                                                if ($access->can_edit == 1) {
                                                    $canedit = 'checked';
                                                }
                                                break;
                                            }
                                        }
                                    }
                                    $stringMapping .= '<tr><td width="15%"><span class="access-dd-handle user-menu-view-control access-controls">
                                                        <input type="checkbox" class="viewmenu" name="menu_view' . $subChildDetail->id . '" ' . $canview . '>
                                                    </span></td>
                                                    <td width="15%"><span class="access-dd-handle user-menu-add-control access-controls">
                                                        <input type="checkbox" class="addmenu" name="menu_add' . $subChildDetail->id . '" ' . $canadd . '>
                                                    </span></td>
                                                    <td width="15%"><span class="access-dd-handle user-menu-edit-control access-controls">
                                                        <input type="checkbox" class="editmenu" name="menu_edit' . $subChildDetail->id . '" ' . $canedit . '>
                                                    </span></td><td width="55%"><input type="hidden" name="menu[]" id="menus" value="' . $subChildDetail->id . '"></td></tr>';
                                }
                            }
                        }
                    }
                }
                $stringMapping .= "</tbody></table>";
                 $stringMapping .= "<div class='col-md-12 mt-2 mb-2 text-center'><input type='submit' id='save-access-type' class='btn btn-primary'></div>";
            }
            if (isset($requestData['company']))
            {
                $roles = '';
                if (!empty($rolesList)) {
                    foreach ($rolesList as $r) {
                        $roles .= '<option value="' . $r->id . '">' . $r->role . '</option>';
                    }
                }
                return json_encode(array('menulist' => $stringMapping, 'roles' => $roles));
            } else {
                return json_encode(array('menulist' => $stringMapping));
            }
        } catch (Exception $ex) {

            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'usermenuAccessTypes', 'MenusController.php');
            // return view('errors.oh!');
        }
    }


    public function userAccessRole(Request $request)
    {
        $requestData = $request->all();
        //dd($requestData); exit;
        try {

            //$masterModel    = new MasterModel();
            //kalyani 27-12-21 $where and $where[id] is not used in this function
            $where       = ['master_code' => 'menu', 'is_deleted' => '0'];
            $where['id']    = $requestData['menu_name'];

            //dd($where['id']);
            $view = "";
            $stringMapping = '';

            $parentmenu = $requestData['menu_name'];
            //dd( $parentmenu); exit;
            $rolesList = RoleModel::whereIn('company_id', [0])->get();
            //dd($rolesList);exit;

            if (isset($rolesList))
             {
                $roleManagement = MenusBaseController::getUserRoleDetails($rolesList[0]->id);
                //dd($roleManagement);//empty
                $parentList = MenusBaseController::getMenuById($requestData['menu_name']);
                //dd($parentList);

                if (!empty($parentList)) {
                    $stringMapping .= '<table style="font-size: 12px; width: 100%;margin-top: -16px;"><tbody>';
                    foreach ($parentList as $parentMenuDetail)
                    {

                        $childList = MenusBaseController::getMenuById($requestData['menu_name'], $parentMenuDetail->id);
                       // dd($childList);//empty

                        if (!empty($roleManagement)) {
                            $canview = $canadd = $canedit = '';
                            foreach ($roleManagement as $access) {
                                if ($parentMenuDetail->id == $access->object_id) {
                                    if ($access->can_view == 1) {
                                        $canview = 'checked';
                                    }
                                    if ($access->can_add == 1) {
                                        $canadd = 'checked';
                                    }
                                    if ($access->can_edit == 1) {
                                        $canedit = 'checked';
                                    }
                                    break;
                                }
                            }
                        }
                        $stringMapping .= '<tr><td width="15%"><span class="access-dd-handle user-menu-view-control access-controls">
                                            <input type="checkbox" class="viewmenu" name="menu_view' . $parentMenuDetail->id . '" ' . $canview . '>
                                            </span></td>
                                            <td width="15%"><span class="access-dd-handle user-menu-add-control access-controls">
                                                <input type="checkbox" class="addmenu" name="menu_add' . $parentMenuDetail->id . '" ' . $canadd . '>
                                            </span></td>
                                            <td width="15%"><span class="access-dd-handle user-menu-edit-control access-controls">
                                                <input type="checkbox" class="editmenu" name="menu_edit' . $parentMenuDetail->id . '" ' . $canedit . '>
                                            </span></td><td width="55%"><input type="hidden" name="menu[]" id="menus" value="' . $parentMenuDetail->id . '"></td></tr>';

                        if (!empty($childList)) {
                            foreach ($childList as $childDetail) {
                                $subChildList = MenusBaseController::getMenuById($requestData['menu_name'], $childDetail->id);
                                if (!empty($roleManagement)) {
                                    $canview = $canadd = $canedit = '';
                                    foreach ($roleManagement as $access) {
                                        if ($childDetail->id == $access->object_id) {
                                            if ($access->can_view == 1) {
                                                $canview = 'checked';
                                            }
                                            if ($access->can_add == 1) {
                                                $canadd = 'checked';
                                            }
                                            if ($access->can_edit == 1) {
                                                $canedit = 'checked';
                                            }
                                            break;
                                        }
                                    }
                                }
                                $stringMapping .= '<tr><td width="15%"><span class="access-dd-handle user-menu-view-control access-controls">
                                                    <input type="checkbox" class="viewmenu" name="menu_view' . $childDetail->id . '" ' . $canview . '>
                                                </span></td>
                                                <td width="15%"><span class="access-dd-handle user-menu-add-control access-controls">
                                                    <input type="checkbox" class="addmenu" name="menu_add' . $childDetail->id . '" ' . $canadd . '>
                                                </span></td>
                                                <td width="15%"><span class="access-dd-handle user-menu-edit-control access-controls">
                                                    <input type="checkbox" class="editmenu" name="menu_edit' . $childDetail->id . '" ' . $canedit . '>
                                                </span></td><td width="55%"><input type="hidden" name="menu[]" id="menus" value="' . $childDetail->id . '"></td></tr>';

                                if (!empty($subChildList)) {
                                    foreach ($subChildList as $subChildDetail) {
                                        if (!empty($roleManagement)) {
                                            $canview = $canadd = $canedit = '';
                                            foreach ($roleManagement as $access) {
                                                if ($subChildDetail->id == $access->object_id) {
                                                    if ($access->can_view == 1) {
                                                        $canview = 'checked';
                                                    }
                                                    if ($access->can_add == 1) {
                                                        $canadd = 'checked';
                                                    }
                                                    if ($access->can_edit == 1) {
                                                        $canedit = 'checked';
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                        $stringMapping .= '<tr><td width="15%"><span class="access-dd-handle user-menu-view-control access-controls">
                                                            <input type="checkbox" class="viewmenu" name="menu_view' . $subChildDetail->id . '" ' . $canview . '>
                                                        </span></td>
                                                        <td width="15%"><span class="access-dd-handle user-menu-add-control access-controls">
                                                            <input type="checkbox" class="addmenu" name="menu_add' . $subChildDetail->id . '" ' . $canadd . '>
                                                        </span></td>
                                                        <td width="15%"><span class="access-dd-handle user-menu-edit-control access-controls">
                                                            <input type="checkbox" class="editmenu" name="menu_edit' . $subChildDetail->id . '" ' . $canedit . '>
                                                        </span></td><td width="55%"><input type="hidden" name="menu[]" id="menus" value="' . $subChildDetail->id . '"></td></tr>';
                                    }
                                }
                            }
                        }
                    }
                    $stringMapping .= "</tbody></table>";
                    $stringMapping .= "<div class='col-md-12 mt-2 mb-2 text-center'><input type='submit' id='save-access-type' class='btn btn-primary'></div>";
                }
            }
            $view = view('Menus::super_admin.menus.access_control', compact('rolesList', 'parentmenu', 'stringMapping'));

            return $view;
        } catch (Exception $ex) {
                $ex->getLine();
                $ex->getMessage() ;
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'userAccessRole', 'MenusController.php');
            // return view('errors.oh!');
        }
    }


    public function save_user_role_access(Request $request)
    {
        try {
            $requestData = $request->all();
            //dd($requestData);exit;
            $parentmenu_id = $requestData['parentmenu'];//408
            $role = $requestData['access_role'];//101
            $menus = $requestData['menu'];
            //dd($menus);
            $role_data = array();
            $company_id = MenusBaseController::get_company_byrole($role);
            //dd( $company_id);exit;
            if (!empty($menus) && !empty($role))
            {
                foreach ($menus as $key => $val)
                 {
                    $role_data[] = array(
                        'company_id' => $company_id,
                        'role_id' => $role,
                        'object_id' => $menus[$key],
                        'object_item_id'=>0,
                        'can_view' => isset($requestData['menu_view' . $menus[$key]]) ? 1 : 0,
                        'can_add' => isset($requestData['menu_add' . $menus[$key]]) ? 1 : '0',
                        'can_edit' => isset($requestData['menu_edit' . $menus[$key]]) ? 1 : '0',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s'),
                    );
                 //dd($role_data);exit;
                }
                $response = MenusBaseController::save_user_role_access($role_data, $role, 10, $menus);
                //dd($response); exit;
            }
            return redirect($_SERVER['HTTP_REFERER']);
            // return redirect('menus')->with('menu_name', $parentmenu_id);
        } catch (Exception $ex) {
            $ex->getLine();
            $ex->getMessage();

            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'save_user_role_access', 'MenusController.php');
            // return view('errors.oh!');
        }
    }

    public function updateUserMenuRoles(Request $request)
    {
        try {
            $requestData = $request->all();
            $response = MenusBaseController::updateUserMenuRoles($requestData);
            if (!empty($response)) {
                return ['status' => 'true', 'data' => $requestData];
            } else {
                return ['status' => 'false', 'data' => $requestData];
            }
        } catch (Exception $ex) {

            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'updateUserMenuRoles', 'MenusController.php');
            // return view('layouts.coming_soon');
        }
    }
}
