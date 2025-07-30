<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\Role;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;

class RoleController extends Controller
{

    public $roleService;

    public function __construct(RoleService $roleService){
       
        $this->roleService = $roleService;
    }

   public function manageRole(Request $request)
   {
      $roles = Role::whereNotIn('name',['SuperAdmin'])->get();
      return view('dashboard.pages.roles.manage_role',compact('roles'));
   }
    
   public function createRole(RoleRequest $request)
   {
        try{
           $validatedData = $request->validated();
           $this->roleService->createRolee($validatedData);
           return response()->json([
               'success'=> true,
               'msg' => 'Role Created'
           ]); 

        }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }

   
   public function updateRole(Request $request)
   {
        try{
           $id = $request->role_id;
           $name = $request->roleName;
           $this->roleService->updateRolee($id,$name);
           return response()->json([
               'success'=> true,
               'msg' => 'Role updated'
           ]); 

        }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }


   public function deleteRole(Request $request)
   {
        try{
           $data = $request->role_id;
           $this->roleService->deleteRolee($data);
           return response()->json([
               'success'=> true,
               'msg' => 'Role deleted'
           ]); 

        }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }

   
}




