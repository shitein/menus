<?php
namespace App\Package\Menus\src\controllers;

use Illuminate\Http\Request;
use App\Export\Export;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        if (session()->has('page_title')) {
            // return Excel::download(new Export(), 'users.csv');
            $file_name = session()->get('page_title').".xlsx";
            return Excel::download(new Export(), $file_name);
        }
    }
}
