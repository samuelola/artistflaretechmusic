<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\PaystackService;
use App\Services\CheckoutService;
use Session;
use DB;
use App\Http\Requests\TransferRequest;
use Illuminate\Support\Str;
use App\Enum\MinimumBalance;
use App\Enum\MinTransferAmount;
use App\Models\UserWallet;
use App\Http\Requests\WalletTransferRequest;
use App\Services\WalletService;
use App\Enum\WalletTransferAmount;


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
        
        //  $rell = $checkoutService->checkformainbalance();
        //  if($rell){
        //     session()->flash('error', "You have &#8358;0.00 in your available wallet,topup your wallet");
        //     return redirect()->back();
        //  }

        $rell = $checkoutService->checkformainbalance();
        if($rell){
            session()->flash('error', "You have &#8358;0.00 in your available wallet,topup your wallet");
            return redirect()->back();
        }

        //  $chekmin = $checkoutService->minimumTransferAmount();
        //  if($chekmin){
        //      $amount = MinTransferAmount::Min;
        //      session()->flash('error', "minimum amount for transfer is  &#8358;{$amount}");
        //      return redirect()->back();
        //  }

        

         $account_number = $request->account_number;
         $account_name = $request->account_name;
         $bank_code = $request->bank;
         $the_amount = $request->amount;
         $currency = 'NGN';
         $reason = $request->reason ?? 'for transfer';
         $getcheckfor = $checkoutService->checkforTransfer($the_amount);
         if($getcheckfor){
            session()->flash('error', "Amount is too low for transfer");
            return redirect()->back();
         }
         $amount = (int)$the_amount;
         $result = $this->paystackService->transferrecipient($account_number,$account_name,$bank_code,$currency);
         //get recipient code
        //  $get_recipient_code = DB::table('transfer_recipient')->where('user_id',auth()->user()->id)->orderBy('id','desc')->first();
        //  $recipient_code = $get_recipient_code->recipient_code;
         $recipient_code = $result->data->recipient_code;
         $source = 'balance';
         $reference = 'REF-' . Str::upper(Str::random(10));
         $rel = $this->transferMoney($recipient_code,$source,$amount,$reference,$reason);

         if($rel->data->status == 'success'){
           // verify transaction
           $verifyTransferRef = $rel->data->reference;
           $resultt = $this->paystackService->verifytransferMoney($verifyTransferRef);
           if($resultt->data->status == 'success'){
              session()->flash('success', "Transfer is successful");
              return redirect()->back();
           }elseif($resultt->data->status == 'failed'){
              session()->flash('error', "Transfer failed");
              return redirect()->back();
           }

         }elseif($rel->data->status == 'otp'){
              session()->flash('error', "Transfer failed because otp is required");
              return redirect()->back();
         }
         
    }

    public function transferMoney($recipient_code,$source,$amount,$reference,$reason){
        
       return  $this->paystackService->transfernewPayment($recipient_code,$source,$amount,$reference,$reason);
    }

    public function resolveAccount(Request $request){

        $bank_code = $request->bank_code;
        $account_number = $request->account_number;
        $result = $this->paystackService->resolve_bank($account_number,$bank_code);
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
   
}
