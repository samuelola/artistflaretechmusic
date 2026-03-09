<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Requests\ChangepasswordRequest;
use App\Services\ChangepasswordService;

class ChangePasswordController extends Controller
{

    public $changepassword;

    public function __construct(ChangepasswordService $changePassword){

        $this->changepassword = $changePassword;
    }
    public function store(ChangepasswordRequest $request,$id){
        
        try{
           $data = $request->validated();
           $changepasswordService = $this->changepassword->updatePass($data,$id);
           return redirect()->back()->with('success','Password changed successfully');
        }catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
       
       
    }

    public function storeUserPassword(ChangepasswordRequest $request,$id){
        
        
        try{

           $data = $request->validated();
           $changepasswordService = $changepassword->updatePass($data,$id);
           return redirect()->back()->with('success','Password changed successfully');

        }catch(\Exception $e){
           return redirect()->back()->with('error',$e->getMessage());
        }
       
    }

    
    
}
