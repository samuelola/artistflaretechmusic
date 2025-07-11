<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UserController extends Controller
{
    public function allUser(Request $request){
        // $get_all_users = DB::table("users")->orderBy('id','desc')->paginate(10);
        $gget_all_users = User::orderBy('id','desc')->get();
        return view("dashboard.pages.users.allusers",compact('gget_all_users'));
    }

    public function deleteUser(Request $request, $id){
        
        $decrypted = decrypt($id);
        User::find($decrypted)->delete();
        return back();
    }
}
