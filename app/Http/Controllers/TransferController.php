<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\PaystackService;
use App\Services\CheckoutService;
use Session;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransferRequest;
use Illuminate\Support\Str;
use App\Enum\MinimumBalance;
use App\Enum\MinTransferAmount;
use App\Models\UserWallet;
use App\Http\Requests\WalletTransferRequest;
use App\Services\WalletService;
use App\Enum\WalletTransferAmount;
use App\Http\Requests\CoinTransferRequest;



class TransferController extends Controller
{
    protected $paystackService;
    public function __construct(PaystackService $paystackService){

       $this->paystackService = $paystackService;
    }

    public function transfer(Request $request)
    {
        $getBanks = DB::table('banks')->get();
        $rels = json_decode($getBanks);
        $get_recipient = UserWallet::where('user_id','!=',auth()->user()->id)->with('user')->get();
        return view('dashboard.pages.transfer',compact('rels','get_recipient'));
    }
    
    public function transferPayment(TransferRequest $request,CheckoutService $checkoutService){
        

         try{

             $checkoutService->walletTransferWithLock(
               $request,
               $paystackService
             );

             return redirect()
            ->route('dashboard')
            ->with('success', 'Your transfer is successful');

         }catch(\Exception $e){
             return redirect()
            ->back()
            ->with('error', $e->getMessage());
         }
  
    }

    

    public function resolveAccount(Request $request){

        $bank_code = $request->bank_code;
        $account_number = $request->account_number;
        $result = $this->paystackService->resolve_bank($account_number,$bank_code);
       
        // Paystack validation error
        if (!$result || $result->status === false) {
            return response()->json([
                'success' => false,
                'message' => $result->message ?? 'Account could not be resolved'
            ]);
        }
        return response([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function getBankList(Request $request){
        
         $rel = $this->paystackService->banklist();
         foreach($rel->data as $val){
                DB::table('banks')->insert([
                    'name' => $val->name,
                    'slug' => $val->slug,
                    'code' => $val->code,
                    'longcode' => $val->longcode,
                    'supports_transfer' => $val->supports_transfer,
                    'active' => $val->active,
                    'country' => $val->country,
                    'currency' => $val->currency,
                    'type' => $val->type
                ]);
            }
         
         
    }

    public function userWalletTransfer(WalletTransferRequest $request,CheckoutService $checkoutService,WalletService $walletService){

    
        // check if account balance
        $rell = $checkoutService->checkformainbalance();
        if($rell){
            session()->flash('error', "You have &#8358;0.00 in your available wallet,topup your wallet");
            return redirect()->back();
        }

        $the_amount = $request->amount_b;
        $recipient = $request->recipient;
        $getcheckfor = $checkoutService->checkforTransfer($the_amount);
        if($getcheckfor){
            session()->flash('error', "Amount is too low for transfer");
            return redirect()->back();
        }

        $min = WalletTransferAmount::Min;
        $checkmintransferAmount = $walletService->checkForMinTransferAmount($min,$the_amount);
        if($checkmintransferAmount){
            session()->flash('error', "You must have a minimum balance of &#8358;{$min}");
            return redirect()->back();
        }

        $walletrel = $walletService->walletTransfer($the_amount,$recipient);
        
        if($walletrel){
            session()->flash('success', "Your transfer is successful");
            return redirect()->route('dashboard');
        }

    }

    public function userCoinTransfer(CoinTransferRequest $request,WalletService $walletService){
       
        $the_amount = $request->amount_c;
        $recipient = $request->recipient;
        $checkmintransferAmount = $walletService->checkForMinTransferCoinAmount($the_amount);
        if($checkmintransferAmount){
            session()->flash('error', "You cannot transfer 0 amount of coins");
            return redirect()->back();
        }

        $walletrel = $walletService->walletCoinTransfer($the_amount,$recipient);
        if($walletrel){
            session()->flash('success', "Your coin transfer is successful");
            return redirect()->route('dashboard');
        }
    }
   
}
