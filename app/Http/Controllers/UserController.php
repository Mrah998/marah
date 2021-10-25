<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function Register(Request $request){
        $validator =Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
                }
                $input = $request->all();
                $input['password'] = Hash::make($input['password']);

                $user = User::create($input);
            
                $user->save();
               
                $success['token'] = $user->createToken('myApp')->accessToken;
                $success['name'] = $user->name;
    return response()->json(['success'=>$success], 200);
        }



        public function login(Request $request)
        {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user(); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                $success['name'] =  $user->name;
       
                return response()->json(['success'=>$success], 200);
            } 
            else{ 
                return response()->json(['message'=>'you are not auth'], 401);
            } 
        }
    
}
