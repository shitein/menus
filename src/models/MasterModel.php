<?php

namespace App\Package\Menus\src\models;

use App\Common\Common;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class MasterModel extends Model
{
        public function insertData($data, $tablename)
        {
            //dd($data);die;
            try
                {
                    $sessionData = session()->get('user_info');
                    if(isset($sessionData) && !empty($sessionData)){
                        $sessionData = session()->get('user_info');
                        $created_by  = $sessionData['user_id'];
                    }
                    else{
                        $created_by = '0';
                    }

                    $currentDate = Carbon::now();
                    $data['created_by'] =  !empty($data['created_by']) ? $data['created_by'] : $created_by ;
                    $data['updated_by'] =  !empty($data['updated_by']) ? $data['updated_by'] : $created_by;
                    $data['created_at'] = $currentDate->toDateTimeString();
                    $data['updated_at'] = $currentDate->toDateTimeString();

                    $rows_affected = DB::table($tablename)->insert($data);
                    //return   $rows_affected ;
                    //dd(  $rows_affected );
                    return DB::getPdo()->lastInsertId();

                }catch(Exception $ex)
                {
                 dd($ex->getMessage()) ;

                 echo $ex->getMessage();

                }
        }


            /**
         * updateData method
         *
         * @access	public
         * @param	string tablename
         * @param	string where
         * @return	update result set
         */

        public function updateData($data, $tablename, $where)
        {
           //dd($tablename);
         try{
            $currentDate    = Carbon::now();
            $sessionData    = session()->get('user_info');

            $query = DB::table($tablename);
            if ($where) {
                foreach ($where as $key => $value) {
                        $query = $query->where($key, $value);
                }
            }

            if(!isset($data['updated_by']) || empty($data['updated_by'])) {
             //uncomment this code auth module add 17-1-2022 kalyani
             //$data['updated_by'] =  isset($data['user_id']) ? $data['user_id'] : $sessionData['user_id'];
            }

            $data['updated_at'] = $currentDate->toDateTimeString();
            $rows_affected = $query->update($data);
            return $rows_affected;

        } catch (Exception $ex) {
            echo $ex->getMessage();
            dd($ex->getLine());
             // $common = new Common();
            // $common->error_logging($ex, 'updateData', 'MasterModel.php');
            // return view('errors.oh!');

        }
    }


             /**
             * get_master_row method
             *
             * @access	public
             * @param	string $tablename
             * @param	string $select
             * @param	string $where
             * @param	array $join
             * @return	single array on success
             */
            public function getMasterRow($tablename,$where = false)
            {

                try {
                    if ($where) {
                        foreach ($where as $key => $value) {
                            $data = DB::table($tablename)->orwhere($key, '=', $value)->first();
                        }
                    }
                    //dd($data);
                    return $data;
                }catch(\Exception $ex){
                    // $common     = new Common();
                    // $common->error_logging($ex, 'getMasterRow', 'MasterModel.php');
                    // return view('layouts.coming_soon');
                 echo    $ex->getMessage();
                }
            }



            /**
             * getMaster method
             *
             * @access	public
             * @param	array join
             * @param	string tablename
             * @param	string where
             * @return	array result set
             */
            public function getMaster($tablename, $select = false, $where = false, $like = false, $likeType = false, $orderBy = false, $join = false)
            {
                try {
                    DB::enableQueryLog();

                    if ($select) {
                        $query = DB::table($tablename)->select($select);
                    } else {
                        $query = DB::table($tablename)->select('*');
                    }

                    if ($where) {
                        if (is_array($where)) {
                            foreach ($where as $key => $value) {
                                $query = $query->where($key, '=', $value);
                            }
                        } else {
                            $query = $query->whereRaw($where);
                        }
                    }

                    /*
                    @ $like       = ['master_code' => 'cnt', 'description' => 'ind'];
                    @                column name => search_string
                    @ $likeType   = 'or';
                    @ Type of like (and, or)
                    */

                    if ($like && $likeType) {
                        if (strtoupper($likeType) == 'AND') {
                            foreach ($like as $key => $value) {
                                //echo "<pre>";print_r($key);print_r($value);

                                $query = $query->Where($key, 'like', '%' . $value . '%');
                            }
                        } else {
                            foreach ($like as $key => $value) {
                                //echo "<pre>";print_r($key);print_r($value);

                                $query = $query->orWhere($key, 'like', '%' . $value . '%');
                            }
                        }
                    }

                    if ($orderBy) {
                        foreach ($orderBy as $key => $value) {
                            $query = $query->orderBy($key, $value);
                        }
                    }
                    if ($join) {
                        if (count($join) > 0) {
                            foreach ($join as $key => $value) {
                                $explode = explode('|', $value);
                                print_r($key);
                                print_r($explode);
                                $query = $query->join($explode[0], $explode[1]);
                            }
                        }
                    }

                    $data = $query->get();
                    return $data;
                    /*return response(array(
                        'error' => false,
                        'data'  => $data,
                    ),200);*/
                }catch(\Exception $ex){
                    // $common     = new Common();
                    // $common->error_logging($ex, 'getMaster', 'MasterModel.php');
                    // return view('layouts.coming_soon');
                    $ex->getMessage();
                }
            }

    /*
       * This function return query with bind dynamic where conditions
     */
    public static function queryBinder($extraSettings = [], $query)
    {
        try {
            if (!empty($extraSettings['where']) && count($extraSettings['where']) > 0) {
                foreach ($extraSettings['where'] as $whereListKey => $whereList) {
                    if ($whereListKey == 'orWhere') {
                        $query->where(function ($q) use ($whereList) {
                            foreach ($whereList as $whereKey => $whereDetail) {
                                $q->OrWhere($whereDetail['column'], $whereDetail['expression'], $whereDetail['value']);
                            }
                        });
                    } else if ($whereListKey == 'where') {
                        foreach ($whereList as $whereKey => $whereDetail) {
                            $query->where($whereDetail['column'], $whereDetail['expression'], $whereDetail['value']);
                        }
                    } else if ($whereListKey == 'whereIn') {
                        foreach ($whereList as $whereKey => $whereDetail) {
                            $query->whereIn($whereDetail['column'], $whereDetail['value']);
                        }
                    } else if ($whereListKey == 'whereNotIn') {
                        foreach ($whereList as $whereKey => $whereDetail) {
                            $query->whereNotIn($whereDetail['column'], $whereDetail['value']);
                        }
                    }
                }
            }
            return $query;
        } catch (\Exception $ex) {
            // $common     = new Common();
            // $common->error_logging($ex, 'queryBinder', 'MasterModel.php');
            // return view('layouts.coming_soon');
        }
    }
}
