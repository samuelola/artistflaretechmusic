<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class FileUploadController extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('image')){
            
            $file = $request->file('image');
            $newFileName = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('profile_uploads'), $newFileName);
            DB::table('users')
            ->where('email', auth()->user()->email)  // find your user by their email
            ->limit(1)
            ->update(['first_name' => $request->first_name,'last_name'=> $request->last_name,'email'=>$request->email,'profile_image'=>$newFileName]);  // update the record in the DB.
            return redirect()->back(); 
        }

            DB::table('users')
            ->where('email', auth()->user()->email)  // find your user by their email
            ->limit(1)
            ->update(['first_name' => $request->first_name,'last_name'=> $request->last_name,'email'=>$request->email]);  // update the record in the DB. 
            return redirect()->back(); 
    }
}




