<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use App\Enum\UserStatus;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    public function allUser(Request $request){

        // $get_all_users = DB::table("users")->orderBy('id','desc')->paginate(10);
        $gget_all_users = User::where('role_id','!=',1)->orderBy('id','desc')->lazy();
        $users = User::distinct('first_name')->count();
        return view("dashboard.pages.users.allusers",compact('gget_all_users','users'));
    }

    public function allActiveUser(Request $request){

        // $get_all_users = DB::table("users")->orderBy('id','desc')->paginate(10);
        $gget_all_users = User::where('active','Yes')->orderBy('id','desc')->get();
        $activeusers = User::distinct('first_name')->where('active','Yes')->count();
        return view("dashboard.pages.users.allactiveusers",compact('gget_all_users','activeusers'));
    }

    public function allInactiveUser(Request $request){

        // $get_all_users = DB::table("users")->orderBy('id','desc')->paginate(10);
        $gget_all_users = User::where('active','No')->orderBy('id','desc')->get();
        $noactiveusers = User::distinct('first_name')->where('active','No')->count();
        return view("dashboard.pages.users.allinactiveusers",compact('gget_all_users','noactiveusers'));
    }

    public function deleteUser(Request $request, $id){
        
        $decrypted = decrypt($id);
        User::find($decrypted)->delete();
        return back();
    }

    public function addNewUser(Request $request){
        $all_countries = DB::table('countries')->get();
        $languages = DB::table('languages')->get();
        return view("dashboard.pages.users.addnew_user",compact('all_countries','languages'));
    }

    public function allState(Request $request)
    {
        $country_id = $request->country_id;
        $all_states = DB::table('states')->where('country_code',$country_id)->get();
        return response([
            'success' => true,
            'data' => $all_states,
        ]);
        
    }

    public function createUser(CreateUserRequest $request,UserService $userservice){
         
        $data = $request->validated();
        $data['active'] = 'Yes';
        $data['deleted'] = 'No';
        $data['albums'] = 0;
        $data['tracks'] = 0;
        $data['role_id'] = UserStatus::User;
        $creatUserService = $userservice->storeUser($data);
        if($creatUserService){
           return redirect()->route('allUser')->with('Success','User Created Successfully');
        }else{
           return redirect()->back()->with('Error','User Created not Successfully'); 
        }
    }


    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }


}
