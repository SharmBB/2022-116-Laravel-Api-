<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Validator;
use DB;
use App\Models\Library;
use Illuminate\Support\Facades\Hash;




class LibraryController extends Controller



{
     function addLibrary(Request $req){

        

        $validator = Validator::make($req->all(), [

            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string'
                   
            
            
        ]);

            if($validator->fails()){
                return response([
                    'error' =>true,
                    'message'=> $validator->errors()
                ]);
            }

        $user = new Library;

        $user->title = $req->title;
        
        $user->description = $req->description;
        
        $user->image = $req->image;
       
        
    

       

        $result = $user->save();

        $result = Library::orderBy('id', 'DESC')->first();



        if($result){
            return response([
                'errorMessage'=>false,
                'message'=>$result 
            ]);
        }
       
    }

   



}
