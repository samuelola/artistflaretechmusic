<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArtistOwnerIdenity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\ArtistOwnershipService;
use App\Http\Requests\Step1Request;
use App\Http\Requests\Step2Request;
use App\Http\Requests\Step3Request;
use Illuminate\Support\Facades\Http;
use App\Services\ArtistRoleRightService;
use App\Services\ArtistSongService;


class ArtistOwnershipIdentityController extends Controller
{
    protected $artistownershipService;
    protected $artistrolerightService;
    protected $songService;


    public function __construct(
        ArtistOwnershipService $artistOwnershipService,
        ArtistRoleRightService $artistRoleRightService,
        ArtistSongService $artistSongService
        )
    {
        $this->artistownershipService = $artistOwnershipService;
        $this->artistrolerightService = $artistRoleRightService;
        $this->songService = $artistSongService;
    }

    
     public function storeStep1(Step1Request $request)
    {
        
       try {

            $artist = $this->artistownershipService->saveStep1($request);

            return response()->json([
                'success' => true,
                'artist_id' => $artist->id,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }


    } 

    public function storeStep2(Step2Request $request)
    {
        try {
            $artistId = $request->user()->artistOwnerIdentity->id; // assuming relation exists
            $artist = $request->user()->artistOwnerIdentity;

            if(!$artist){
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete Step 1 first'
                ], 400);
            }

            $coOwners = [];

            if($request->ownership_type === 'co' && $request->co_owners) {
                $coOwners = $request->co_owners; // array of co-owners
            }

            $role = $this->artistrolerightService->saveStep2(
                $artistId, 
                array_merge($request->validated(), [
                'co_owners' => $coOwners
            ]));

            return response()->json([
                'success' => true,
                'role_id' => $role->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeStep3 (Step3Request $request)
    {
            try {
                $artistId = $request->user()->artistOwnerIdentity->id;
                $songs = $request->songs;

                $savedSongs = $this->songService->saveSongs($artistId, $request);

                return response()->json([
                    'success' => true,
                    'count' => count($savedSongs)
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
    
    }
}
