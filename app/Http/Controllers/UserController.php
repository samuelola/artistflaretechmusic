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
    public $userService;

    public function __construct(UserService $userService){

        $this->userService = $userService;
    }

    public function allUser(Request $request){

        // $get_all_users = DB::table("users")->orderBy('id','desc')->paginate(10);
        $allusers = $this->userService->Users();
        $gget_all_users = $allusers['allusers'];
        $users = $allusers['users'];
        return view("dashboard.pages.users.allusers",compact('gget_all_users','users'));
    }

    public function allActiveUser(Request $request){

        // $get_all_users = DB::table("users")->orderBy('id','desc')->paginate(10);
        $allusers = $this->userService->activeUsers();
        $gget_all_users = $allusers['allusers_active'];
        $activeusers = $allusers['users_count'];
        return view("dashboard.pages.users.allactiveusers",compact('gget_all_users','activeusers'));
    }

    public function allInactiveUser(Request $request){

        // $get_all_users = DB::table("users")->orderBy('id','desc')->paginate(10);
        $allusers = $this->userService->inActiveUsers();
        $gget_all_users = $allusers['allusers_inactive'];
        $noactiveusers = $allusers['usersinactive_count'];
        return view("dashboard.pages.users.allinactiveusers",compact('gget_all_users','noactiveusers'));
    }

    public function deleteUser(Request $request, $id){
        
        $decrypted = decrypt($id);
        User::find($decrypted)->delete();
        return back();
    }

    public function addNewUser(Request $request){
        
        $all = $this->userService->userGeo();
        $all_countries = $all['countries'];
        $languages = $all['alllang'];
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

    public function createUser(CreateUserRequest $request){
         
       
        try{
            $data = $request->validated();
            $data['active'] = 'Yes';
            $data['deleted'] = 'No';
            $data['albums'] = 0;
            $data['tracks'] = 0;
            $data['role_id'] = UserStatus::User;
            $creatUserService =  $this->userService->storeUser($data);
            return redirect()->route('allUser')->with('Success','User Created Successfully');

        }catch(\Exception $e){
            return redirect()->back()->with('Error',$e->getMessage());
        }
       
    }


    public function export() 
    {
        $this->userService->ExportUsers();
    }


}
