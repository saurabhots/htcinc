<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
class TaskController extends Controller
{
    public function index(Request $request)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $tasks = Task::all();
            if($tasks){
                return response()->json(['status' => 'success','tasks'=>$tasks]);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    public function create(Request $request)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:tasks|min:5|max:50',
                'description' => 'required|string|min:5|max:50',
                'project_id' => 'required',
                'user_id'   => 'required'
            ]);
             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }
            $create = Task::create([
                'title' => $request['title'],
                'description' => $request['description'],
                'project_id' => $request['project_id'],
                'user_id' => $request['user_id'],
                "status" => "NOT_STARTED"
            ]);
            if($create){
                return response()->json(['status' => 'Task created successfully'], 201);
            } else {
                return response()->json(['status' => 'Data is not created'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    public function getTaskById(Request $request,$id)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $task = Task::find($id);
            if($task){
                return response()->json(['status' => 'success','task'=>$task]);
            } else {
                return response()->json(['status' => 'Data is not available'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $validator = Validator::make($request->all(), [
                'title' => 'string|min:5|max:50',
                'description' => 'string|min:5|max:50',
                'status' => 'string|in:NOT_STARTED,IN_PROGRESS,READY_FOR_TEST,COMPLETED'
            ]);
             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }
            $task = Task::where('id',$id)->update($request->all());
            if($task){
                return response()->json(['status' => 'Task updated successfully'], 200);
            } else {
                return response()->json(['status' => 'Data is not updated'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    public function delete(Request $request,$id)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $task = Task::find($id);
            if($task){
                Task::where('id',$id)->delete();
                return response()->json(['status' => 'Task deleted successfully'], 200);
            } else {
                return response()->json(['status' => 'Data is not deleted'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }
}