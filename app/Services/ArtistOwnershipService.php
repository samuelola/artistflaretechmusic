<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\ArtistOwnerIdentity;
use Illuminate\Support\Facades\Http;

class ArtistOwnershipService
{
    public function saveStep1($request)
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('government_id')) {
            $imageFile = $request->file('government_id');
            $response = Http::withHeaders([
                'X-APP-A-KEY' => env('APP_A_API_KEY'),
                ])
                ->attach(
                    'government_id',
                    file_get_contents($imageFile->getRealPath()),
                    $imageFile->getClientOriginalName()
                )->post(config('app.website_storage_link')."/api/upload_goverment_id");
            $dataa = $response->json();
            $data['government_id_path'] = $dataa['path'];    
        }

        $data['user_id']= auth()->id();
        $artist = ArtistOwnerIdentity::create($data);
        if(!$artist){
            
            throw new \Exception ("Artist Identity not submitted");
        }

        return $artist;
    }
}