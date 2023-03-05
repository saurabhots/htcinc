<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;

class UserController extends Controller
{
    //This function used to get user list
    public function index(Request $request)
    { 
        if($request->user()->user_role == 'ADMIN'){
            $users = User::all('name','username','user_role');
            if($users){
                return response()->json(['status' => 'success','data'=>$users],200);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    //This function used to create user record
    public function create(Request $request)
    {
        if($request->user()->user_role == 'ADMIN'){
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users|min:2|max:50',
                'name' => 'required|string|min:2|max:50',
                'password' => 'required|min:6|max:10',
                'user_role'=>'required|string|in:ADMIN,PRODUCT_OWNER,TEAM_MEMBER'
            ]);
             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 403);
            }
            $create = User::create([
                'name' => $request['name'],
                'username' => $request['username'],
                'password' => Hash::make($request['password']),
                'user_role' => $request['user_role'],
            ]);
            return response()->json(['status' => 'User has been created successfully'], 201);
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    //This function used to get user record by user_id
    public function getUserById(Request $request,$user_id)
    {
        if($request->user()->user_role == 'ADMIN'){
            $user = User::where('id',$user_id);
            if($user){
                return response()->json(['status' => 'success','data'=>$user],200);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    //This function used to update user record
    public function update(Request $request, $user_id)
    {
        if($request->user()->user_role == 'ADMIN'){
            $validator = Validator::make($request->all(), [
                'username' => 'string|min:2|max:50|unique:users,username,'.$user_id,
                'name' => 'string|min:2|max:50',
                'user_role'=>'string|in:ADMIN,PRODUCT_OWNER,TEAM_MEMBER'
            ]);
             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 403);
            }
            $updateUser = User::where('id',$user_id)->update($request->all());
            if($updateUser){
                return response()->json(['status' => 'User info has been updated successfully'], 200);
            } else {
                return response()->json(['status' => 'Data is not updated'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    //This function used to delete user record
    public function delete(Request $request,$user_id)
    {
        if($request->user()->user_role == 'ADMIN'){
            $user = User::find($user_id);
            if($user->id){
                User::where('id',$user_id)->delete();
                return response()->json(['status' => 'User record has been deleted successfully'], 200);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
            
    }
}