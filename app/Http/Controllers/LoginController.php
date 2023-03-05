<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where("username", $request["username"])->first();
        if($request->has('password')){
            if ($user && Hash::check($request["password"], $user->password)) {
                $token = $user->createToken('API Token')->plainTextToken;
                User::where('id',$user->id)->update(['oauth_token' => $token]);
                return response()->json(['status' => 'Log-in','token'=>$token]);
            }else{
                $msg = array('msg'=>'Invalid Credentials');
                return response()->json(['errors' => $msg], 401);
            }
        }else{
            $msg = array('msg'=>'Please enter your password.');
            return response()->json(['errors' => $msg], 401);
        }
    }
}