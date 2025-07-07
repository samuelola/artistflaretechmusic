<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;


class ChangepasswordService{

    public function updatePass($storeSub,$id){
        $rel = (array)$storeSub;
        $userChange =  User::find($id)->update(['password'=> Hash::make($rel['new_password'])]);
        return $userChange;    
    }

    
}