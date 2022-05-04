<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's

    Route::get("all",[UserController::class,'get']);


    

    });

    //Author
    Route::post("add",[AuthorController::class,'add']);
    Route::get("getAllUsers",[AuthorController::class,'get']);
    Route::get("getbyId/{id}",[AuthorController::class,'getUserById']);
    Route::post("Register",[AuthorController::class,'UserRegister']);
    Route::post("Login",[AuthorController::class,'UserLogin']);
    Route::post("checkotp",[AuthorController::class,'OTPcheck']);
    Route::delete("delete/{id}",[AuthorController::class,'delete']);
    Route::put("reset",[AuthorController::class,'ResetPassword']);


    



// Admin 
Route::post("login",[UserController::class,'index']);
Route::post("otpCheckAdmin",[UserController::class,'OTPcheckAdmin']);
Route::put("resetAdmin",[UserController::class,'ResetPasswordAdmin']);





// Route::post("reset",[UserController::class,'ResetPassword']);


//Pathai Admin
Route::put("change",[UserController::class,'ChangePassword']);
Route::post("OTP",[UserController::class,'OTPSent']);
Route::put("Reset",[UserController::class,'ResetPassword']);






//Point CRUD
Route::get("getPoints",[PointController::class,'getAllPoints']);
Route::post("addPoint",[PointController::class,'addPoint']);
Route::put("updatePoint",[PointController::class,'updatePointById']);


//Route CRUD
Route::get("getRoutes",[RouteController::class,'getAllRoutes']);
Route::post("addRoute",[RouteController::class,'addRoute']);
Route::put("updateRoute",[RouteController::class,'updateRouteById']);
Route::delete("deleteRoute",[RouteController::class,'deleteRoute']);


Route::get("getRoute",[PointController::class,'getRoute']);
Route::post("getRouteForUser",[PointController::class,'getRouteForUser']);


Route::delete("deleteRouteAdmin",[RouteController::class,'deleteRouteById']);




