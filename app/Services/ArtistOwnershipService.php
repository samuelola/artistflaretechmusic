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
        $data['catalog_status'] = 'draft';
        $artist = ArtistOwnerIdentity::create(
            $data 
        );
        if (empty($artist->artist_code)) {
            $artist->artist_code = $this->generateArtistCode($artist->id);
            $artist->save();
        }
        if(!$artist){
            
            throw new \Exception ("Artist Identity not submitted");
        }

        return $artist;
    }

    /**
     * Generate unique artist code like FLR-CAT-20394
     */
    protected function generateArtistCode($id)
    {
        // You can use a prefix + padded ID or random number
        return 'FLR-CAT-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
}