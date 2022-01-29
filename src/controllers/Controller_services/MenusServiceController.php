<?php

namespace Shitein\Menus\controllers\Controller_services;

use App\Package\Menus\src\controllers\Base\MenusBaseController;
use App\Package\Menus\src\controllers\Controller;
use App\Package\Menus\src\models\MasterModel;
use App\Package\Menus\src\models\Menus;
use App\Package\Menus\src\models\MenusMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MenuMapping;
use Exception;

class MenusServiceController extends Controller
{
    public static function store_menus($requestData)
    {
        //dd($requestData);
        try {
            $masterModel    = new MasterModel();
            $insertMenuData['short_code']               = $requestData['short_code'];
            $insertMenuData['description']              = $requestData['description'];
            $insertMenuData['url']                      = $requestData['url'];
            $insertMenuData['class']                    = $requestData['class'];
            $insertMenuData['font_icon_class']          = $requestData['font_icon_class'];
            $insertMenuData['language_key']             = $requestData['language_key'];
            $insertMenuData['styles']                   = $requestData['styles'];//ADD line kalyani.
            $insertMenuData['is_deleted']   = 0;

            $inserted_id = $masterModel ->insertData($insertMenuData, 'menus');
            return $inserted_id;

        } catch (Exception $ex)
        {
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'store_menus', 'MenusServiceController.php');
            // return view('errors.oh!');
            $ex->getMessage();
        }
    }

    public static function update_menus($requestData)
    {
        try {
           // dd($requestData);
            $masterModel  = new MasterModel();
            $updateData['short_code']           = $requestData['short_code'];
            $updateData['description']          = $requestData['description'];
            $updateData['url']                  = $requestData['url'];
            $updateData['class']                = $requestData['class'];
            $updateData['font_icon_class']      = $requestData['font_icon_class'];
            $updateData['language_key']         = $requestData['language_key'];
            $updateData['styles']               = $requestData['styles'];//ADD line kalyani.
            $where1['id']                       = $requestData['old_id'];
           // dd($updateData);
            $masterModel ->updateData($updateData, 'menus', $where1);
            //dd(  $result);
            unset($updateData['language_key']);
            $where2['menu_id'] = $requestData['old_id'];
            //dd(  $where2['menu_id'] );//5;
            $response = $masterModel ->updateData($updateData, 'menu_mapping', $where2);
            // dd($response);//1
            return $response;


        } catch (Exception $ex) {
         echo   $ex->getMessage();
             // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'update_menus', 'MenusServiceController.php');
            // return view('errors.oh!');
        }
    }


    public static function delete_menu_mappings($requestData)
    {
        try {
            $masterModel  = new MasterModel();
            //$response = "";
            if (isset($requestData['targetId'])) {
                $updateMenuMapping['is_deleted'] = 1;
                $where['id'] = $requestData['targetId'];
                $response =  $masterModel->updateData($updateMenuMapping, 'menu_mapping', $where);
            }
            return $response;
        } catch (Exception $ex) {

            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'delete_menu_mappings', 'MenusServiceController.php');
            // return view('errors.oh!');
        }
    }


    public static function save_menu_mappings($requestData)
    {
        //dd($requestData);
        try {
            $response['msg'] = 'Someting went wrong!!!';
            $masterModel  = new MasterModel();
            $menuID = $requestData['menuID'];
            $menuDetail = Menus::where('id', '=', $menuID)->first();

            if (isset($requestData['menu_name'])) {

                $where['id'] = $requestData['menu_name'];
                $menuMasterDetail = $masterModel->getMasterRow('general_master',$where);

                if (!empty($menuMasterDetail))
                 {
                    $insertMenuMappingData['menu_master_id']    = $requestData['menu_name'];
                    $insertMenuMappingData['menu_id']           = $menuDetail->id;
                    $insertMenuMappingData['parent_id']         = 0;
                    $insertMenuMappingData['reference_id']      = 0;
                    $insertMenuMappingData['short_code']        = $menuDetail->short_code;
                    $insertMenuMappingData['description']       = $menuDetail->description;
                    $insertMenuMappingData['sequence']          = 0;
                    $insertMenuMappingData['url']               = $menuDetail->url;
                    $insertMenuMappingData['class']             = $menuDetail->class;//add code kalyani
                    $insertMenuMappingData['font_icon_class']   = $menuDetail->font_icon_class;//add code kalyani
                    $insertMenuMappingData['is_deleted']        = 0;
                    //$mappingID = DB::table('menu_mapping')->insert($insertMenuMappingData);

                    $mappingID =$masterModel->insertData($insertMenuMappingData, 'menu_mapping');
                }
                $response['msg'] = 'Menu Added successfully';
            } else {
                $response['msg'] = 'Please select one Parent Menu for mapping';
            }
            return $response;
        } catch (Exception $ex) {
            echo $ex->getLine();
            dd($ex->getMessage());
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'save_menu_mappings', 'MenusServiceController.php');
            // return view('errors.oh!');

        }
    }
    public static function update_menu_list(Request $request)
    {
        try {
            $term = $request->all()['list'];
            $list = json_decode($term, true);
           // dd(  $list );
            $masterModel=new MasterModel();

            if (isset($request['menu_name']) && !empty($request['menu_name'])) {
                $where['id']       = $request['menu_name'];
                $masterMenusDetail =$masterModel->getMasterRow('general_master', $where);
                //dd(  $masterMenusDetail);
                if (!empty($masterMenusDetail)) {
                    if (!empty($list)) {
                        $parentSequence = 1;
                        foreach ($list as $detail) {
                           // dd($detail['menuid']);//2
                            $menuDetails = MenusMapping::where('menu_id', '=', $detail['menuid'])->first();
                            //dd(   $menuDetails);

                            if (!empty($menuDetails)) {
                                MenusBaseController::update_menu_mapping($detail['menuid'], $request['menu_name'], $parentSequence);
                                $id = $menuDetails->id;
                               // dd($id );//2

                            } else {
                                $mapping = new MenusMapping();
                                $mapping->menu_master_id    = $request['menu_name'];
                                $mapping->menu_id           = $detail['menuid'];
                                $mapping->parent_id         = '0';
                                $mapping->reference_id      = '0';
                                $mapping->short_code        = $detail['shortcode'];
                                $mapping->sequence          = $parentSequence;
                                $mapping->url               = $detail['url'];
                                $mapping->save();
                                $id = $mapping->id;
                            }

                            if (!empty($detail['children'])) {
                                $childSequence = 1;
                              //  dd($detail['children'] );
                                foreach ($detail['children'] as $child) {

                                    $menuChildDetails = MenusMapping::where('menu_id', '=', $child['menuid'])->first();

                                    if (!empty($menuChildDetails)) {
                                        MenusBaseController::update_menu_mapping($child['menuid'], $request['menu_name'], $childSequence, $id);
                                        $subID = $menuChildDetails->id;
                                    } else {
                                        $mapping = new MenusMapping();
                                        $mapping->menu_master_id = $request['menu_name'];
                                        $mapping->menu_id = $child['menuid'];
                                        $mapping->parent_id = $id;
                                        $mapping->reference_id = '0';
                                        $mapping->short_code = $child['shortcode'];
                                        $mapping->sequence = $childSequence;
                                        $mapping->url = $child['url'];
                                        $mapping->save();
                                        $subID = $mapping->id;
                                    }

                                    if (isset($child['children'])) {
                                        $subChildSequence = 1;
                                        foreach ($child['children'] as $subChild) {
                                            $menuChildDetails = MenusMapping::where('menu_id', '=', $subChild['menuid'])->first();

                                            if (!empty($menuChildDetails)) {
                                                MenusBaseController::update_menu_mapping($subChild['menuid'], $request['menu_name'], $subChildSequence, $subID);
                                            } else {
                                                $mapping = new MenusMapping();
                                                $mapping->menu_master_id = $request['menu_name'];
                                                $mapping->menu_id = $subChild['menuid'];
                                                $mapping->parent_id = $subID;
                                                $mapping->reference_id = '0';
                                                $mapping->short_code = $subChild['shortcode'];
                                                $mapping->sequence = $subChildSequence;
                                                $mapping->url = $subChild['url'];
                                                $mapping->save();
                                            }
                                            $subChildSequence++;
                                        }
                                    }
                                    $childSequence++;
                                }
                            }
                            $parentSequence++;
                        }
                    }
                }
                $response = $term;
            } else {
                $response = "0";
            }
            return $response;
        } catch (Exception $ex) {
            echo ($ex->getMessage());
            dd($ex->getLine());
            // $ErrorlogBaseController = new ErrorlogBaseController();
            // $ErrorlogBaseController->error_logging($ex,'update_menu_list', 'MenusServiceController.php');
            // return view('errors.oh!');
        }
    }

}
