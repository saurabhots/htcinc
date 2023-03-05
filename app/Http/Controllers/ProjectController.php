<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Project;
use Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $projects = Project::where('name','like','%'.$request['q'].'%')->orderBy($request['sortBy'],$request['sortDirection'])->limit($request['pageSize'])->offset($request['pageSize']*$request['pageIndex'])->get();
            if($projects){
                return response()->json(['status' => 'success','projects'=>$projects],200);
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
                'name' => 'required|unique:projects|min:3|max:100',
            ]);
             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }
           
            $create = Project::create([
                'name' => $request['name']
            ]);
            if($create){
                return response()->json(['status' => 'Project has been created successfully'], 201);
            } else {
                return response()->json(['status' => 'Data is not created'], 200);
            }
            
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }
    public function getProjectById(Request $request,$id)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $project = Project::find($id);
            return response()->json(['status' => 'success','project'=>$project]);
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if($request->user()->user_role == 'PRODUCT_OWNER'){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:100'
            ]);
             
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }
            $is_update = Project::where('id',$id)->update([
                'name' => $request['name']
            ]);
            if($is_update){
                return response()->json(['status' => 'Project has been updated successfully'], 200);
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
            $project = Project::find($id);
            if($project){
                $project->delete();
                return response()->json(['status' => 'Project has been deleted successfully'], 200);
            } else {
                return response()->json(['status' => 'Data is not created'], 200);
            }
        }else{
            return response()->json(['errors' => 'Unauthorized Access'], 401);
        }
    }
}