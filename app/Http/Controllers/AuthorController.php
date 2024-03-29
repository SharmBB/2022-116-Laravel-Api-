<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Validator;
use DB;
use App\Models\Author;
use Illuminate\Support\Facades\Hash;
use App\Models\Route;

use App\Models\Point;



use Mail;





class AuthorController extends Controller
{


    function UserRegister(Request $req){

        

        $validator = Validator::make($req->all(), [
            'email' => 'required|email|unique:authors',
                   
            'password' => [
            'required',
            'string',
            'min:8',             // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/', // must contain a special character

                
            
        ]]);

            if($validator->fails()){
                return response([
                    'error' =>true,
                    'message'=> $validator->errors()
                ]);
            }

        $user = new Author;

        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        
    

       

        $result = $user->save();

        $result = Author::orderBy('id', 'DESC')->first();



        if($result){
            return response([
                'errorMessage'=>false,
                'message'=>$result 
            ]);
        }
       
    }

   



    //login

    function UserLogin(Request $request)
    {
        $user= Author::where('email', $request->email)->first();

        //Validator  
        $validator = Validator::make($request->all(),[
          
            'password' => [
            'required',
            'string',
            'min:10',             // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/', // must contain a special character
        ],   
        ]);

        if($validator->fails()){
            return response([
                'error' =>true,
                'message'=> $validator->errors()
            ]);
            
        }

      
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'errorMessage' => true,
                    'message' => 'Email or password  do not match with the records.'
                ]);
                
            }
        
                 $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token,
                'errorMessage' => false,
                 'message' => 'Login Sucessfully'
            ];
        
             return response($response, 201);
            
    }

    

 



   

      // get all users
      function get(){
        
        return Author::all();
    }




     function getUserById(Request $request,$id){
        $user=Author::find($request->id);

        if(!$user){
            return response([
                'errorMessage' => true,
                'message'=>'User Not Available'
            ]);
        }
    
        
        return $user;
    }

    


   

   





    //otpcheck

    function OTPcheck(Request $request)
    {
        $user= Author::where('email', $request->email)->first();
        

        //Validator  
        $validator = Validator::make($request->all(),[
          
            'otp' => [
            'required',
            'string',
           
        ],   
        ]);

        if($validator->fails()){
            return response([
                'error' =>true,
                'message'=> $validator->errors()
            ]);
            
        }

      
        if (!$user || $request->otp != $user->otp ) {
                return response([
                    'errorMessage' => true,
                    'message' => 'OTP failed'
                ]);
                
            }
        
           
        
            $response = [
              
               
                'errorMessage' => false,
                 'message' => 'OTP is correct'
            ];
        
             return response($response, 201);
            
    }




    //delete user
    function delete($id){
        $user=Author::find($id);
        if(is_null($user)){
            return response()->json('Record not found in Database',404);
        }
        $result=$user->delete();
        if($result){
            return ["result"=>"User is delete "];
        }
        else{
            return ["result"=>" delete failed"];
        }
    }

    

 //REset Password
 function ResetPassword(Request $request){

    $user= Author::where('email', $request->email)->first();


      
    if (!$user  ) {
        return response([
            'errorMessage' => true,
            'message' => 'Email not found'
        ]);
        
    }

    $user->password = Hash::make($request->newPassword);
    
    $result = $user->save();

    return response([
        'errorMessage' => false,
        'message' => 'changed Sucessfully'
    ]);
    
}
}

        
        


   





 