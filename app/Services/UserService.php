<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class UserService{

    public function storeUser($storeUser){
        $rel = (array)$storeUser;
        $user =  User::create($rel);
        return $user;
        
    }

    
}