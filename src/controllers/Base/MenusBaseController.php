<?php

namespace Shitein\Menus\controllers\Base;

use App\Package\Menus\src\controllers\Controller;
use Illuminate\Http\Request;
use App\Package\Menus\src\models\Menus;
use App\Package\Menus\src\models\MenusMapping;
use App\Package\Menus\src\models\RoleManagement;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class MenusBaseController extends Controller
{
    public static function getMenuList()
    {
        try
        {
            $menuMappingList = MenusMapping::all('menu_id');
            $menuList = Menus::whereNotIn('id', $menuMappingList)->get();
            return $menuList;
        } catch (Exception $ex)
        {

             // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'getMenuList', 'MenusBaseController.php');
            // return view('errors.oh!');
        }
    }


    public static function getMenuById($menuid, $parentid = "", $active = null)
    {
        try
         {
            $query = MenusMapping::select('menu_mapping.*', 'menus.description', 'menus.language_key','menus.styles')
                ->leftJoin('menus', 'menu_mapping.menu_id', '=', 'menus.id')
                ->where('parent_id', '=', $parentid);
            //dd($query);

            if (!empty($active))
            {
                $query->where('menus.is_deleted', 0);
            }

            $menulist = $query->where('menu_master_id', '=', $menuid)->orderBy('sequence', 'ASC')->get();
            //dd($menulist);
            return $menulist;
        } catch (Exception $ex)
        {

              // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'getMenuById', 'MenusBaseController.php');
            // return view('errors.oh!');
        }
    }


    public static function update_menu_mapping($menuid, $menu, $sequence, $parentid = 0)
    {
     // dd($sequence);//1
     //dd($menuid);//3
     //dd($menu);//408
    // dd($parentid);
        try
        {
            $data = array(
                            'menu_master_id' => $menu,
                            'parent_id' => $parentid,
                            'sequence' => $sequence
                         );
            //dd($data);
            $response = DB::table('menu_mapping')->where('menu_id', $menuid)->limit(1)->update($data);
           // dd( $response);//0
           return $response;

        } catch (Exception $ex)
        {
            echo $ex->getMessage();
            dd($ex->getLine());
              // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'update_menu_mapping', 'MenusBaseController.php');
            // return view('errors.oh!');
        }
    }

    public static function getUserRoleDetails($role)
    {
        //dd($role);exit;
        try{
            $roleManagement = DB::table('role_management')
                                 ->select('role_management.*')
                                 ->where('role_management.role_id', $role)->get();
            //dd($roleManagement);exit;
            return $roleManagement;
        }catch(Exception $ex){

              // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'getUserRoleDetails', 'MenusBaseController.php');
            // return view('errors.oh!');
        }
    }
    public static function save_user_role_access($role_data,$role,$company_id,$menus)
    {
        //dd($role_data);exit;
        //dd($menus);exit;
        try
        {
            if(!empty($menus))
            {
                $deleteresponse=DB::table('role_management')->where('role_id',$role)->where('company_id',$company_id)
                ->whereIn('object_id',$menus)->delete();
                //print_r($role_data);exit;

                $response=DB::table('role_management')->insert($role_data);
                //dd($response);

                if($response){
                    return true;
                }else{
                    return false;
                }
            }
        }catch(Exception $ex){
            $ex->getLine();
            $ex->getMessage() ;
              // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'save_user_role_access', 'MenusBaseController.php');
            // return view('errors.oh!');
        }

    }
    public static function updateUserMenuRoles($requestData){
        try{
            // Commneted By Swapnil on 01-Dec-2021 (Old Code)
            // if ($requestData['column'] == 'can_view') {
            //     $dataUpdate = array(
            //         'can_view' => $requestData['value'],
            //         'can_add' => $requestData['value'],
            //         'can_edit' => $requestData['value']
            //     );
            //     $response = DB::table('role_management')->where('id', $requestData['id'])->update($dataUpdate);
            // } else {
            //     $response = DB::table('role_management')->where('id', $requestData['id'])->update([
            //         $requestData['column'] => $requestData['value']
            //     ]);
            // }
            // return $response;
            // Code Added By Swapnil on 01-Dec-2021 (New Code with roles checkbox issue fix)
            $response = '';
            if ($requestData['column'] == 'can_view' && $requestData['value'] == '0') {
                $dataUpdate = array(
                    'can_view' => $requestData['value'],
                    'can_add' => $requestData['value'],
                    'can_edit' => $requestData['value']
                );
                $response = DB::table('role_management')->where('id', $requestData['id'])->update($dataUpdate);
            } else {
                $dataUpdate = array(
                    $requestData['column'] => $requestData['value']
                );
                if (isset($requestData['set_view'])) {
                    if ($requestData['set_view'] == '1') {
                        $dataUpdate['can_view'] = 1;
                    } else {
                        $dataUpdate['can_view'] = 0;
                    }
                }
                $response = DB::table('role_management')->where('id', $requestData['id'])->update($dataUpdate);
            }
            return $response;
        }catch(Exception $ex){

              // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'updateUserMenuRoles', 'MenusBaseController.php');
            // return view('errors.oh!');
        }

    }

    public static function get_company_byrole($role){
        try{
            $companydata=DB::table('roles')->select('company_id')->where('id',$role)->first();
            //dd($companydata);exit;
            if(!empty($companydata)){
                return $companydata->company_id;
            }else{
                return false;
            }

        }catch(Exception $ex){

              // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'get_company_byrole', 'MenusBaseController.php');
            // return view('errors.oh!');
        }
    }
}
