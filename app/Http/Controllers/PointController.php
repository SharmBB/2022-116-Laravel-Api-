<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Route;
use DB;
use Illuminate\Support\Facades\Validator;


class PointController extends Controller
{
    
    function addPoint(Request $req){

        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'route_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $route =  Route::find($req->route_id);
        if(!$route){
            return response([
                'errorMessage' => true,
                'message'=>'Route Id is wrong'
            ]);
        }

        $point = new Point;

        $point->name = $req->name;
        $point->latitude = $req->latitude;
        $point->time = $req->time;
        $point->longitude = $req->longitude;
        $point->route_id = $req->route_id;

        $result = $point->save();
        if($result){
            return response([
                'errorMessage'=>false,
                'message'=>'Point Added Successfully!!!'
            ]);
        }
        else{
            return response([
                'errorMessage' => true,
                'message'=>'Failed'
            ]);
        }
    }

    function getAllPoints(){
        return Point::all();
    }

    function getPointById(Request $req){

        $validator = Validator::make($req->all(), [
            'id' => 'required|inetger',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $point = Point::find($req->id);
        if(!$point){
            return response([
                'errorMessage' => true,
                'message'=>'Point Not Available'
            ]);
        }

        return $point;
    }

    function updatePointById(Request $req){
        $point = Point::find($req->id);

        if(!$point){
            return response([
                'errorMessage' => true,
                'message'=>'Point Not Available'
            ]);
        }
        
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'route_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $point->name = $req->name;
        $point->latitude = $req->latitude;
        $point->time = $req->time;
        $point->longitude = $req->longitude;
        $point->route_id = $req->route_id;

        $result = $point->save();
        if($result){
            return response([
                'errorMessage'=>false,
                'message'=>'Point Updated Successfully!!!'
            ]);
        }
        else{
            return response([
                'errorMessage' => true,
                'message'=>'Failed'
            ]);
        }
    }

    function deletePointById(Request $req){
        $point = Point::find($req->id);

        if(!$point){
            return response([
                'errorMessage' => true,
                'message'=>'Point Not Available'
            ]);
        }
        
        $result = $point->delete();

        if($result){
            return response([
                'errorMessage' => false,
                'message'=>'Point Deleted Successfully!!!'
            ]);
        }
        else{
            return response([
                'errorMessage' => true,
                'message'=>'Failed'
            ]);
        }
    }

    function getRoute($latitude,$longitude){

        // $validator = Validator::make($req->all(), [

        //     'latitude' => 'required|numeric',
        //     'longitude' => 'required|numeric',

        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }
        
        return DB::select("SELECT routes.id as RouteId,number as RouteNumber,routes.name as RouteName 
        FROM points,routes WHERE routes.id = points.route_id 
        and longitude=$longitude and points.id in (SELECT id FROM `points` WHERE latitude=$latitude);
        ");
        
    }

    function getRouteForUser(Request $req){
        $output = array();
        $result1  = $this->getRoute($req->latitude1,$req->longitude1);
        $result2  = $this->getRoute($req->latitude2,$req->longitude2);

        foreach($result1 as $key){
            foreach ($result2 as $var) {
                if($key->RouteId == $var->RouteId){
                    array_push($output,$key);
                } 
            }
        }

        return $output;


    }
}


