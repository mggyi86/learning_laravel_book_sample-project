<?php

namespace App\Http\Controllers;

use App\Queries\GridQueries\GridQuery;
use App\Queries\GridQueries\WidgetQuery;
use Illuminate\Http\Request;
use DB;

class ApiController extends Controller
{
    public function widgetData(Request $request)
    {
//        $rows = Widget::select('id as Id', 'name as Name', 'created_at as Created')->paginate(10);
/*        $rows = DB::table('widgets')->select('id as Id', 'name as Name', DB::raw('DATE_FORMAT(created_at, "%m-%d-%Y") as Created'))->paginate(10);
        return response()->json($rows);*/

        return GridQuery::sendData($request, new WidgetQuery);
    }
}
