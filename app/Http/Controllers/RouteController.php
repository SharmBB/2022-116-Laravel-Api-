<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\Point;
use DB;

class RouteController extends Controller
{
    //
    function addRoute(Request $req){

        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'number' => 'required|integer',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $route = new Route;

        $route->name = $req->name;
        $route->number = $req->number;

        $result = $route->save();

        $result = Route::orderBy('id', 'DESC')->first();



        if($result){
            return response([
                'errorMessage'=>false,
                'message'=>$result 
            ]);
        }
        else{
            return response([
                'errorMessage' => true,
                'message'=>'Failed'
            ]);
        }
    }

    function getAllRoutes(){
        return Route::all();
    }

    function getRouteByid(Request $req){
        $route  = Route::find($req->id);

        if(!$route){
            return response([
                'errorMessage' => true,
                'message'=>'Route Not Available'
            ]);
        }

        return $route;
        
    }


    function updateRouteById(Request $req){

        $route = Route::find($req->id);


        if(!$route){
            return response([
                'errorMessage' => true,
                'message'=>'Route Not Available'
            ]);
        }

        if(!$route){
            return response([
                'errorMessage' => true,
                'message'=>'Route Not Available'
            ]);
        }

        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'number' => 'required|integer',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $route->name = $req->name;
        $route->number = $req->number;

        $result = $route->save();

        if($result){
            return response([
                'errorMessage'=>false,
                'message'=>'Route Updated Successfully!!!'
            ]);
        }
        else{
            return response([
                'errorMessage' => true,
                'message'=>'Failed'
            ]);
        }

    }

    


    function deleteRouteById(Request $req){

        //validating inputs
        $validator = Validator::make($req->all(),[
            'id'   => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $result = Point::where('route_id',$req->id)->get();

        if($result){
            $result = Point::where('route_id',$req->id)->delete();
        }

        $route = Route::find($req->id);

        if(!$route){
            return response([
                'errorMessage' => true,
                'message'=>'Route Not Available'
            ]);
        }

        $result = $route->delete();

        if($result){
            return response([
                'errorMessage'=>false,
                'message'=>'Route Deleted Successfully!!!'
            ]);
        }
        else{
            return response([
                'errorMessage' => true,
                'message'=>'Failed'
            ]);
        }
    }
}
