<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Exports\UsersExport;
use DB;

class UserService{

    public function storeUser($storeUser){
        $rel = (array)$storeUser;
        $user =  User::create($rel);
        if(!$user){
           throw new \Exception ("User Created not Successfully");
        }
        return $user;
        
    }

    public function Users(){

       $gget_all_users = User::where('role_id','!=',1)->orderBy('id','desc')->lazy();
       $users = User::distinct('first_name')->count();

       return [
           'allusers' => $gget_all_users,
           'users'=> $users
       ];
    }


    public function activeUsers(){

       $gget_all_users_active = User::where('active','Yes')->orderBy('id','desc')->get();
       $activeuserscount = User::distinct('first_name')->where('active','Yes')->count();

       return [
           'allusers_active' => $gget_all_users_active,
           'users_count'=> $activeuserscount
       ];
    }

    public function inActiveUsers(){

        $gget_all_usersinactive = User::where('active','No')->orderBy('id','desc')->get();
        $noactiveuserscount = User::distinct('first_name')->where('active','No')->count();

       return [
           'allusers_inactive' => $gget_all_usersinactive,
           'usersinactive_count'=> $noactiveuserscount
       ];
    }

    public function userGeo(){

        $all_countries = DB::table('countries')->get();
        $languages = DB::table('languages')->get();

        return [
            'countries' => $all_countries,
            'alllang'   => $languages
        ];
    }

    public function ExportUsers(){

        return Excel::download(new UsersExport, 'users.xlsx');
    }

    
}