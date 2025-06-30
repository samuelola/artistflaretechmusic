<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class NewwAuthController extends Controller
{
    
    public function loginUserr(Request $request){

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];
        if (Auth::attempt($credentials)) 
        {
            $token = Auth::user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user' => Auth::user(), 
                'token' => $token
            ], 200);
            // return redirect()->route('dashboard')->with('token', $token);
        }
  
        return response()->json([
            'error_message' => 'Unauthorised, Wrong Email or Password'
        ], 401);
    }

    public function userData (Request $request){
         
        if (Auth::user()) {
            $token = $request->bearerToken();
            
            return response()->json([ 
                'user_details' => auth()->user(),
                'user_token' => $token
            ]);
        }
    }

    public function logout (Request $request){
         
        if (Auth::user()) {
            $user = request()->user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return true;
        }
    }

    
}
