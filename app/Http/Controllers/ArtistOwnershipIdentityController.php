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
use App\Http\Requests\Step4Request;
use App\Http\Requests\Step5Request;
use Illuminate\Support\Facades\Http;
use App\Services\ArtistRoleRightService;
use App\Services\ArtistSongService;
use App\Services\SongContributorService;
use App\Services\ArtistRightsService;



class ArtistOwnershipIdentityController extends Controller
{
    protected $artistownershipService;
    protected $artistrolerightService;
    protected $artistsongservice;
    protected $contributorService;
    protected $artistRightsService;


    public function __construct(
        ArtistOwnershipService $artistOwnershipService,
        ArtistRoleRightService $artistRoleRightService,
        ArtistSongService $artistSongService,
        SongContributorService $contributorService,
        ArtistRightsService $rightsService
        )
    {
        $this->artistownershipService = $artistOwnershipService;
        $this->artistrolerightService = $artistRoleRightService;
        $this->artistsongservice = $artistSongService;
        $this->contributorService = $contributorService;
        $this->artistRightsService = $rightsService;
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

                $this->artistsongservice->saveSongs($artistId, $request);

                return response()->json([
                    'success' => true,
                    'message' => 'Songs are being uploaded in background'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
    
    }

    public function storeStep4(Step4Request $request)
    {
       
        $songsData = $request->input('data');

        try {
            $saved = $this->contributorService->saveContributors($songsData);

            return response()->json([
                'success' => true,
                'message' => 'Contributors saved successfully',
                'saved_count' => count($saved)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save contributors: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeStep5(Step5Request $request)
    {

        $artistId = $request->user()->artistOwnerIdentity->id;

        try {
            $this->artistRightsService->saveRights($artistId,$request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Step 5 saved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save rights: '.$e->getMessage()
            ], 500);
        }
    }

    
}
