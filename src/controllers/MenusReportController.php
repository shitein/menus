<?php

namespace Shitein\Menus\src\controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Export\Export;

class MenusReportController extends Controller
{

    public function index(Request $request)
    {
        $requestData = $request->all();
        try {
            $addEditFormKey     = '';
            $filterFormData ='';
            $filterFields ='';
            /* KALYANI 28-dec-2021
                 $filterFormData and $filterFields  are declared empty.
            */
            // $filterFormData     = DB::table('forms')->where('form_key', '=', 'lead_details_report')->first();
            // $filterFields       = DB::table('form_fields')->where('form_id', $filterFormData->id)
            // ->where('is_deleted', '=', '0')->orderBy('sequence', 'asc')->get();

            // $reportAttribute = DB::table('report')->where('report.route_name', '=', 'lead_details_report')
            //     ->leftjoin('report_attribute', 'report_attribute.report_id', '=', 'report.id')->get();

            $settings = [
                'action_edit_button'        => '0',
                'action_view_button'        => '0',
                'action_accordion_button'   => '0',
                'accordion_url'             => '',
                'add_button'                => '0',
                'pagination'                => '1',
                'filter_button'             => '0',
                'group_button'              => '0',
                'export_button'             => '1',
                'import_button'             => '0',
                'search'                    => '1',
                'list_title'                => 'Menus Report',
                'action_header'             => '0',
                'multiSaveKey'              => '',
                'report'                    => '0',
                'report_footer'             => ''
            ];
            $headers = [
                'menu_id'                        => 'Menu ID',
                'id'                             => 'Mapping ID',
                'level_description'              => 'Level Description',
                'short_code'                     => 'Short Code',
                'description'                    => 'Decription',
                'url'                            => 'URL',
                'sequence'                       => 'Mapping SEQ'
            ];
            $select         =   [
                'menus.id AS menu_id',
                'menu_mapping.id AS id',
                DB::raw('CONCAT(menu_mapping.menu_master_id," - ",general_master.description) as level_description'),
                'menu_mapping.short_code',
                'menu_mapping.description',
                'menu_mapping.url',
                'menu_mapping.sequence'
            ];

            $query = DB::table('menu_mapping')
                ->select($select)
                ->leftJoin('general_master', 'general_master.id', '=', 'menu_mapping.menu_master_id')
                ->leftJoin('menus','menus.id','=','menu_mapping.menu_id');

            /*---- Filter query ----*/
            // if (isset($requestData['filterSubmit'])) {
            //     foreach ($filterFields as $filterKey => $filterVal) {
            //         $filter         = $filterFields[$filterKey]->name; //filter5
            //         $filterColumn   = $filterFields[$filterKey]->db_mapping; // created_at
            //         if (isset($requestData[$filter])) {
            //             $query = $query->whereIn($filterColumn, $requestData[$filter]);
            //         }
            //     }
            // }

            if (isset($requestData['basics']) && !empty($requestData['basics'])) {
                $query->where(function ($query) use ($requestData) {
                    $query->where('menu_mapping.menu_id', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('menu_mapping.id', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('menus.id', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('menu_mapping.menu_master_id', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('general_master.description', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('menu_mapping.short_code', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('menu_mapping.description', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('menu_mapping.url', 'LIKE', '%' . $requestData['basics'] . '%')
                        ->orwhere('menu_mapping.sequence', 'LIKE', '%' . $requestData['basics'] . '%');
                });
            }

            if ($request->ajax()) {
                if (isset($requestData['filter_column_name']) && isset($requestData['sorting_method']) && !empty($requestData['filter_column_name']) && !empty($requestData['sorting_method'])) {
                    $query = $query->orderBy($requestData['filter_column_name'], $requestData['sorting_method']);
                }
            }else{
                $query = $query->orderBy('menus.id', 'DESC');
            }

            $export     = new Export();
            $sql_query  = $export->eloquentSqlWithBindings($query);

            $export->setExcelParameters($sql_query, $headers, $settings['list_title']);
            $paginationData = $query->paginate(4);
            $listingData            = json_decode(json_encode($paginationData), true);

            if ($request->ajax()) {
                return view('Menus::layouts.listing', compact('headers', 'listingData', 'settings', 'paginationData', 'addEditFormKey', 'filterFormData', 'filterFields'));
            }
            return view('Menus::layouts.listing-container', compact('headers', 'listingData', 'settings', 'paginationData', 'addEditFormKey', 'filterFormData', 'filterFields'));
        } catch (Exception $ex) {
            dd($ex->getMessage());
            $ex->getLine();
        }
    }
}
