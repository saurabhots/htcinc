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
    public function index()
    {
            $users = User::all('name','username','user_role');
            if($users){
                return response()->json(['status' => 'success','data'=>$users]);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
    }

    //This function used to create user record
    public function create(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users|min:2|max:10',
                'name' => 'required|string|min:2|max:50',
                'password' => 'required|min:6|max:10',
                'user_role'=>'required|string|min:3|max:15'
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
    }

    //This function used to get user record by user_id
    public function getUserById($user_id)
    {
            $user = User::where('id',$user_id);
            if($user){
                return response()->json(['status' => 'success','data'=>$user]);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
    }

    //This function used to update user record
    public function update(Request $request, $user_id)
    {
            $validator = Validator::make($request->all(), [
                'username' => 'string|min:2|max:10|unique:users,username,'.$user_id,
                'name' => 'string|min:2|max:50',
                'user_role'=>'string|min:3|max:15'
            ]);
             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 403);
            }
            $updateUser = User::where('id',$user_id)->update($request->all());
            if($updateUser){
                return response()->json(['status' => 'User info has been updated successfully'], 200);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
    }

    //This function used to delete user record
    public function delete($user_id)
    {
            $user = User::find($user_id);
            if($user->id){
                User::where('id',$user_id)->delete();
                return response()->json(['status' => 'User record has been deleted successfully'], 200);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
            
    }
}