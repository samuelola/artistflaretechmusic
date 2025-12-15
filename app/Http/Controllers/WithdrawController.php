<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CryptoCurrency;
use App\Http\Requests\WithdrawCoinRequest;
use App\Services\WithdrawalService;
use DB;
use App\Models\CryptoWallet;

class WithdrawController extends Controller
{
    public function withdrawCoin(Request $request){

        $cryptocurrencies = CryptoCurrency::select('symbol')->where('status',1)->get();
        return view('dashboard.pages.withdraw',compact('cryptocurrencies'));
    }

    public function withdrawStoreCoin(WithdrawCoinRequest $request, WithdrawalService $withdrawalService){

        $user   = $request->user();
        $coin   = $request->coin;
        $amount = $request->amount;
        $address = $request->address;
        $wallet = $user->cryptowallet()->where('coin', $coin)->first();

        if (!$wallet || $wallet->balance < $amount) {

            return back()->with('error',"Insufficient {$coin} balance.");
            
        }

        // $r = $withdrawalService->deduct($user,$coin,$amount);

        // if($r){
        //     return redirect()->route('dashboard')->with('success','Withdraw is Successful');
        // }

        return redirect()->route('dashboard')->with('success','Page under construction ');
        
        
    }
}
