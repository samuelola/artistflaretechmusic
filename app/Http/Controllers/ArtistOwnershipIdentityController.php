<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArtistOwnerIdenity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ArtistOwnershipIdentityController extends Controller
{
     public function storeStep1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'stage_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:artists,phone',
            'email' => 'required|email|unique:artists,email',
            'youtube' => 'nullable|url',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'id_type' => 'required|string|in:Passport,National ID,Driver\'s License',
            'government_id' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Save uploaded file
        $govIdPath = $request->file('government_id')->store('government_ids', 'public');

        $artist = ArtistOwnerIdenity::create([
            'full_name' => $request->full_name,
            'stage_name' => $request->stage_name,
            'dob' => $request->dob,
            'nationality' => $request->nationality,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'youtube' => $request->youtube,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'tiktok' => $request->tiktok,
            'id_type' => $request->id_type,
            'government_id_path' => $govIdPath,
        ]);

        return response()->json(['success' => true, 'artist_id' => $artist->id]);

    } 
}
