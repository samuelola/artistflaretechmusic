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
    public function store(ChangepasswordRequest $request, ChangepasswordService $changepassword,$id){
        $data = $request->validated();
        $changepasswordService = $changepassword->updatePass($data,$id);
        if($changepasswordService){
            return redirect()->back()->with('success','Password changed successfully');
        }
       
    }

    public function storeUserPassword(ChangepasswordRequest $request, ChangepasswordService $changepassword,$id){
        $data = $request->validated();
        $changepasswordService = $changepassword->updatePass($data,$id);
        if($changepasswordService){
            return redirect()->back()->with('success','Password changed successfully');
        }
       
    }

    
    
}
