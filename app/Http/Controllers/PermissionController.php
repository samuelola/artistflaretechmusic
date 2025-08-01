<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permission;
use App\Http\Requests\PermissionRequest;
use App\Services\PermissionService;
use App\Models\Role;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Route;
use App\Models\PermissionRoute;


class PermissionController extends Controller
{

   public $permissionService;

   public function __construct(PermissionService $permissionService)
   {
       
        $this->permissionService = $permissionService;
   }
    
    
   public function managePermission(Request $request)
   {
      $permissions = Permission::all();
      return view('dashboard.pages.permissions.manage_permission',compact('permissions'));
   }
    
   public function createPermission(PermissionRequest $request)
   {
        try{
           $validatedData = $request->validated();
           $validatedData['slug'] = $request->name;
           $validatedData['groupbyy'] = 1;
           $this->permissionService->createPermission($validatedData);
           return response()->json([
               'success'=> true,
               'msg' => 'Permission Created'
           ]); 

        }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }

   public function deletePermission(Request $request)
   {
        try{
           $data = $request->permission_id;
           $this->permissionService->deletePermission($data);
           return response()->json([
               'success'=> true,
               'msg' => 'Permission deleted'
           ]); 

        }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }

   public function updatePermission(Request $request)
   {
        try{
           $id = $request->permission_id;
           $name = $request->permissionName;
           $this->permissionService->updatePermission($id,$name);
           return response()->json([
               'success'=> true,
               'msg' => 'Permission updated'
           ]); 

        }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }


   public function assignPermissionRole(Request $request)
   {
      $roles = Role::all();
      $permissions = Permission::all();
      $permission_with_roles = Permission::with('roles')->whereHas('roles')->get();
      return view('dashboard.pages.permissions.assign_permission_role',compact('roles','permissions','permission_with_roles'));
   }

   public function createPermissionRole(Request $request)
   {
       try{
        
        $ifExistPermissionToRole = PermissionRole::where([
            'role_id'=> $request->role_id,
            'permission_id'=> $request->permission_id,
         ])->first();

         if($ifExistPermissionToRole){ 
            return response()->json([
               'success'=> false,
               'msg' => 'Permission is already assigned to selected role!'
           ]);
         }

         PermissionRole::create([
            'role_id'=> $request->role_id,
            'permission_id'=> $request->permission_id,
         ]);
 
         return response()->json([
               'success'=> true,
               'msg' => 'Permission is assigned to selected role!'
           ]);


       }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }

   public function editPermissionRole(Request $request,$id)
   {
       //$roles = Role::whereNotIn('name',['SuperAdmin'])->get();
       $roles = Role::all();
       $permissions = Permission::all();
       $permission_with_roles = Permission::with('roles')->whereHas('roles')->get();
       $getPermissionRoles = PermissionRole::where('permission_id',$id)->get();
       $getPermissionRolew = Permission::where('id',$id)->first();
       $selectedRoleIds = [];
       foreach($getPermissionRoles as $val){
           $selectedRoleIds[] = $val->role_id;
       }

       
       return view('dashboard.pages.permissions.edit_permission_role',compact(
         'roles',
         'permissions',
         'permission_with_roles',
         'getPermissionRoles',
         'selectedRoleIds',
         'getPermissionRolew'
      ));
   }


   public function updatePermissionRole(Request $request)
   {
       $permission = $request->permission_id;
       $roles  =  $request->role_ids;
       PermissionRole::where('permission_id',$permission)->delete();
       $insertData = [];
       foreach($roles as $role){
           $insertData[] = [
                'permission_id'=> $request->permission_id,
                'role_id' => $role
           ];
       }

      PermissionRole::insert($insertData);
      return redirect()->route('assign_permission_role');
   }

   public function deletePermissionRole(Request $request)
   {
       try{
           $data = $request->permission_id;
           $ids = PermissionRole::where('permission_id',$data)->get();
           foreach ($ids as $value) {
              $result_data =  PermissionRole::where('permission_id',$value->permission_id)->delete();
           }
           
           return response()->json([
               'success'=> true,
               'msg' => 'Permission deleted'
           ]); 

        }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }


   public function assignPermissionRoute(Request $request)
   {
      $routes = Route::getRoutes();
      $middlewareGroup = 'superadmincheck';
      $routeDetails = [];
      foreach($routes as $route){
         $middlewares = $route->gatherMiddleware();
         if(in_array($middlewareGroup, $middlewares)){
             $routeName = $route->getName();
             if($routeName !== 'dashboard' && $routeName !== 'dashboard.logout'){
                 $routeDetails[] = [
                 'name' => $routeName,
                 'url'  => $route->uri()
                ];
             }
            
         }
      }

      $permissions = Permission::all();
      $routerPermissions = PermissionRoute::with('permission')->get();
      $permission_with_roles = Permission::with('roles')->whereHas('roles')->get();
      return view('dashboard.pages.permissions.assign_permission_route',compact('permissions','permission_with_roles','routeDetails','routerPermissions'));
   }

   public function createPermissionRoute(Request $request)
   {
       try{
        
        $ifExist = PermissionRoute::where([
            'permission_id'=> $request->permission_id,
         ])->first();

         if($ifExist){ 
            return response()->json([
               'success'=> false,
               'msg' => 'Permission is already assigned!'
           ]);
         }
         PermissionRoute::create([
            'router'=> $request->route,
            'permission_id'=> $request->permission_id,
         ]);
 
         return response()->json([
               'success'=> true,
               'msg' => 'Permission is assigned to selected route!'
           ]);


       }catch(\Exception $e)
        {
           return response()->json([
               'success'=> false,
               'msg' => $e->getMessage()
           ]);
        }
   }


   public function editPermissionRoute(Request $request,$id)
   {

      $routes = Route::getRoutes();
      $middlewareGroup = 'superadmincheck';
      $routeDetails = [];
      foreach($routes as $route){
         $middlewares = $route->gatherMiddleware();
         if(in_array($middlewareGroup, $middlewares)){
             $routeName = $route->getName();
             if($routeName !== 'dashboard' && $routeName !== 'dashboard.logout'){
                 $routeDetails[] = [
                 'name' => $routeName,
                 'url'  => $route->uri()
                ];
             }
            
         }
      }
       
       $permissions = Permission::all();
       $getPermissionRolew = Permission::where('id',$id)->first();
       $permission_route = PermissionRoute::where('id',$id)->first();

       return view('dashboard.pages.permissions.edit_permission_route',compact(
         'permissions',
         'routeDetails',
         'getPermissionRolew',
         'permission_route'
      ));
   }


   public function updatePermissionRoute(Request $request)
   {
       $permission = $request->permission_id;
       $routes  =  $request->routes;
       PermissionRoute::where('permission_id',$permission)->delete();
       $insertData = [];
       foreach($routes as $route){
           $insertData[] = [
                'permission_id'=> $request->permission_id,
                'router' => $route
           ];
       }

      PermissionRoute::insert($insertData);
      return redirect()->route('assign_permission_route');
   }

}
