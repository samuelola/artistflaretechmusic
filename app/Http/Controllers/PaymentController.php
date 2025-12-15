<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\MusicRelease;

class PaymentController extends Controller
{
    public function Payments(Request $request)
    {
        $release_products = MusicRelease::with(['user','tracks','artworks'])
        ->withCount('tracks')
        ->where([
            'user_id' => auth()->user()->id,
            'status' => 'submitted',
            'distributed' => 'yes'
        ])
        ->paginate(1); 
        $product_count = MusicRelease::
        where([
            'user_id' => auth()->user()->id,
            'status' => 'submitted',
            'distributed' => 'yes'
        ])
        ->count();
        $payments = Payment::get();
        return view('dashboard.pages.payments.payment',
        compact('payments','release_products','product_count')
       );
    }

    public function Earnings(Request $request)
    {
       return view('dashboard.pages.payments.earnings');
    }

    public function splitSheet(Request $reques)
    {
        return view('dashboard.pages.payments.split_sheet');
    }

    
    public function getTracks($releaseId)
    {
        $release = MusicRelease::where('id', $releaseId)
            ->where('user_id', auth()->id())
            ->where('status', 'submitted')
            ->with(['tracks.participants'])
            ->firstOrFail();

        return response()->json($release->tracks);
    }

}
