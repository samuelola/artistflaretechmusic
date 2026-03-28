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
use App\Http\Requests\Step6Request;
use App\Http\Requests\FinalSubmitRequest;
use Illuminate\Support\Facades\Http;
use App\Services\ArtistRoleRightService;
use App\Services\ArtistSongService;
use App\Services\SongContributorService;
use App\Services\ArtistRightsService;
use App\Services\ArtistOwnershipPaymentService;
use App\Services\ArtistOwnCatalogSubmissionService;
use App\Models\ArtistOwnerIdentity;
use App\Models\ArtistOwnerSong;



class ArtistOwnershipIdentityController extends Controller
{
    protected $artistownershipService;
    protected $artistrolerightService;
    protected $artistsongservice;
    protected $contributorService;
    protected $artistRightsService;
    protected $artistpaymentService;
    protected $submissionservice;


    public function __construct(
        ArtistOwnershipService $artistOwnershipService,
        ArtistRoleRightService $artistRoleRightService,
        ArtistSongService $artistSongService,
        SongContributorService $contributorService,
        ArtistRightsService $rightsService,
        ArtistOwnershipPaymentService $artistpaymentService,
        ArtistOwnCatalogSubmissionService $submissionService
        )
    {
        $this->artistownershipService = $artistOwnershipService;
        $this->artistrolerightService = $artistRoleRightService;
        $this->artistsongservice = $artistSongService;
        $this->contributorService = $contributorService;
        $this->artistRightsService = $rightsService;
        $this->artistpaymentService = $artistpaymentService;
        $this->submissionservice = $submissionService;
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
            
            $user = auth()->user();
            // $artist = $request->user()->artistOwnerIdentity;
            $artist = ArtistOwnerIdentity::where('user_id', $user->id)
            ->where('catalog_status', 'draft')
            ->latest()
            ->first();

            $artistId = $artist->id; 

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

                $user = auth()->user();
                $artist = ArtistOwnerIdentity::where('user_id', $user->id)
                ->where('catalog_status', 'draft')
                ->latest()
                ->first();
                $artistId = $artist->id;
                $songs = $request->songs;

                $newsongs = $this->artistsongservice->saveSongs($artistId, $request);

                return response()->json([
                    'success' => true,
                    'artist_id' => $artistId,
                    'songs'=> $newsongs,
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

        $user = auth()->user();
            // $artist = $request->user()->artistOwnerIdentity;
            $artist = ArtistOwnerIdentity::where('user_id', $user->id)
            ->where('catalog_status', 'draft')
            ->latest()
            ->first();

        $artistId = $artist->id; 

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

    public function storeStep6(Step6Request $request)
    {
        try {

            $user = auth()->user();
            $artist = ArtistOwnerIdentity::where('user_id', $user->id)
            ->where('catalog_status', 'draft')
            ->latest()
            ->first();

            $artistId = $artist->id; 

            $this->artistpaymentService->save($artistId, $request);

            return response()->json([
                'success' => true,
                'message' => 'Payment info saved'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function finalSubmit(FinalSubmitRequest $request)
    {
        try {

            $user = auth()->user();
            $artist = ArtistOwnerIdentity::with(['user'])->where('user_id', $user->id)
            ->where('catalog_status', 'draft')
            ->latest()
            ->first();

            $artistId = $artist->id; 

            $this->submissionservice->submit($artistId, $request,$artist);

            ArtistOwnerIdentity::where('id',$artistId)->update([
                  'catalog_status' => 'submitted',
            ]);

            

            return response()->json([
                'success' => true,
                'message' => 'Submitted successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function step4Data($artistId)
    {
         $songsOwner = ArtistOwnerSong::where('artist_ownership_identity_id', $artistId)
        ->with('contributors')
        ->get();

        

        return response()->json([
            'songs' => $songsOwner
        ]);
    }

    
}
