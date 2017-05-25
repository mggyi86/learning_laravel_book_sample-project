<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Queries\GridQueries\GridQuery;
use App\Queries\GridQueries\WidgetQuery;
use App\Queries\GridQueries\MarketingImageQuery;
use App\Queries\GridQueries\CategoryQuery;
use App\Queries\GridQueries\SubcategoryQuery;

class ApiController extends Controller
{
    public function widgetData(Request $request)
    {
//        $rows = Widget::select('id as Id', 'name as Name', 'created_at as Created')->paginate(10);
/*        $rows = DB::table('widgets')->select('id as Id', 'name as Name', DB::raw('DATE_FORMAT(created_at, "%m-%d-%Y") as Created'))->paginate(10);
        return response()->json($rows);*/

        return GridQuery::sendData($request, new WidgetQuery);
    }

    public function marketingImageData(Request $request){
        return GridQuery::sendData($request, new MarketingImageQuery);
    }

    public function categoryData(Request $request)
    {
        return GridQuery::sendData($request, new CategoryQuery);
    }

    public function subcategoryData(Request $request)
    {
        return GridQuery::sendData($request, new SubcategoryQuery);
    }
}
