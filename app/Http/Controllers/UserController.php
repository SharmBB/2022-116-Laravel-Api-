<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\Facades\Validator;

class UserController extends Controller
{
    function index(Request $request)
    {
        $user= User::where('email', $request->email)->first();

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

    

    //get
    function get(){
        
        return User::all();
    }


  


    //Change password
    function ChangePassword(Request $request){
        

        
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

          'new_password' => [
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
                'error' => true,
                'message'=>$validator->errors(),
                
            ]);
        }

        $user=User::find($request->id);
        if(is_null($user)){
            return response([
                'errorMessage' => true,
                'message' => 'Record not found in database'
            ]);
        }
        if (!(Hash::check($request->get('password'), $user->password))) {
        
              return response([
                'errorMessage' => true,
                'message' => 'Current password does not match!'
            ]);
        }

        if(strcmp($request->get('password'), $request->get('new_password')) == 0){
            
            return response([
                'errorMessage' => true,
                'message' => 'Current Password and New Password are Same !!!'
            ]);;
        }

        if (!Hash::check($request->password, $user->new_password)) {
          
        
             $user->password = Hash::make($request->new_password);
             $user->save();

        return response([
            'errorMessage' => false,
            'message' => 'Password Sucessfully Changed!'
        ]);
    }
        
    }


    //OTP
    function OTPSent(Request $req){
        $email=$req->email;
        $user= User::where('email', $req->email)->first();
       
        if(!$user)
       {
        return response([
            'errorMessage' => true,
            'message' => 'Record not found!'
        ]);
       }
       else
       {
  
        $otp = mt_rand(100000, 999999);
  
        $emailData = [
              
              'email' => $user->email,
              'OTP'=> $otp,
              'subject' => "OTP has been sent!",
              'fromEmail' => "abc@gmail.com",
              'fromName' => "Pathai Development"
          ];  
  
          $data = array('email'=> $user->email,'OTP'=> $otp);
          Mail::send('OtpValidate', $data, function($message) use ($emailData){
             $message->to( $emailData['email'], $emailData['OTP'])
             ->subject($emailData['subject']);
             $message->from( $emailData['fromEmail'],$emailData['fromName']);
          });
  
          
          $user->otp = $otp;
          $result = $user->save();
     
       return response([
            'errorMessage' => false,
            'message' => 'OTP Sent Sucessfully!'
        ]);
  
    }
    }



    // //Reset Password
    // function ResetPassword(Request $req){

    //        $validator = Validator::make($req->all(),[
    //       'newPassword' => [
    //         'required',
    //         'string',
    //         'min:10',             // must be at least 10 characters in length
    //         'regex:/[a-z]/',      // must contain at least one lowercase letter
    //         'regex:/[A-Z]/',      // must contain at least one uppercase letter
    //         'regex:/[0-9]/',      // must contain at least one digit
    //         'regex:/[@$!%*#?&]/', // must contain a special character
    //     ],
    //     ]);

    //     if($validator->fails()){
    //         return response([
    //             'error' => true,
    //             'message'=>$validator->errors(),
                
    //         ]);
    //     }

        

    //     $user= User::where('email', $req->email)->first();
         
    //         if (!$user || $req->otp != $user->otp) {
    //             return response([
    //                 'errorMessage' => true,
    //                 'message' => 'Your otp or mail do not match our records.'
    //             ]);
        
    //         }

    //         $user->password = Hash::make($req->newPassword);

    //         $result  = $user ->save();
    //        return response([
    //         'errorMessage' => false,
    //         'message' => 'Password Sucessfully Reset!'
    //     ]);

    //    }


    
    //otpcheck

    function OTPcheckAdmin(Request $request)
    {
        $user= User::where('email', $request->email)->first();
      //  $user1= User::where('otp', $request->otp);
      

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
                'message' => 'OTP not match.'
            ]);
            
        }
                

                
            
        
           
        
            $response = [
              
               
                'errorMessage' => false,
                 'message' => 'OTP is correct'
            ];
        
             return response($response, 201);
            
    
        }

 //REset Password
 function ResetPasswordAdmin(Request $request){

    $user= User::where('email', $request->email)->first();


      
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

