<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\PaystackService;
use Session;
use App\Http\Requests\TopupRequest;

class TopUpController extends Controller
{

    protected $paymentService;

    public function __construct(PaystackService $paymentService)
    {
         $this->paymentService = $paymentService;
    }

    public function topup(Request $request)
    {
        return view('dashboard.pages.topup');
    }

    public function saveTopup(TopupRequest $request){
        
        $user_id = auth()->user()->id;
        $email = $request->email;
        $amount = $request->amount;
        $rel = $this->paymentService->initalizePayment($amount,$email,$user_id);
        return redirect()->to($rel->data->authorization_url);
    }

    public function paymentCallback(Request $request)
    {
        $reference = $request->reference;
        $user_id = $request->user_id;
        $result = $this->paymentService->verifyPayment($reference,$user_id);
        if($result){
            session()->flash('success', "Topup is successful");
            return redirect()->route('dashboard');
        } 
    }
}
