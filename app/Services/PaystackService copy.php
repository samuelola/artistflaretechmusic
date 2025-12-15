<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\PaymentInterface;
use DB;
use App\Models\UserStatistics;
use App\Enum\MinimumBalance;


class PaystackService implements PaymentInterface
{

    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.paystack.url', 'https://api.paystack.co');
        $this->secretKey = config('services.paystack.secret','sk_test_ecbf9d30f9330b81001f54a86729ff52d1d66a87');
    }

    


    public function initalizePayment($amount,$email,$user_id){

        $newamount = (int)$amount;
        $callback_url = route('paystack.payment_callback',$user_id);
        $fields = [
            'email' => $email,
            'amount' => $newamount * 100,
            'callback_url' => $callback_url
        ];
        $data_string = http_build_query($fields);
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseUrl.'/transaction/initialize',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$this->secretKey,
                    "Cache-Control: no-cache",
                    ),
              ));
        
        //execute post
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $ress = json_decode($response);
        return $ress;
    }

    public function verifyPayment($reference,$user_id)
    {
         // verify transaction 
        $curl = curl_init(); 
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl.'/transaction/verify/'.$reference,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$this->secretKey,
            "Cache-Control: no-cache",
            ),
        ));
        $new_response = curl_exec($curl);
        $err = curl_error($curl);
        $ress_new = json_decode($new_response);
        // send anywhere

        $reference = $ress_new->data->reference;
        $status = $ress_new->data->status;
        $amount = $ress_new->data->amount/100;
        $gateway_response = $ress_new->data->gateway_response;
        $paid_at = $ress_new->data->paid_at;
        $currency = $ress_new->data->currency;
        $authorization_code = $ress_new->data->authorization->authorization_code;
        $last4 = $ress_new->data->authorization->last4;
        $exp_month = $ress_new->data->authorization->exp_month;
        $exp_year = $ress_new->data->authorization->exp_year;
        $card_type = $ress_new->data->authorization->card_type;
        $bank = $ress_new->data->authorization->bank;
        $country_code = $ress_new->data->authorization->country_code;
        $customer_email = $ress_new->data->customer->email;
        $customer_name = $ress_new->data->customer->first_name;
        $fees = $ress_new->data->fees;

        $check_auto = DB::table('user_auto_code')->where('user_id',auth()->user()->id)->first();

        if(is_null($check_auto)){
           
            DB::table('user_auto_code')->insert(
            [
                'user_id' => auth()->user()->id,
                'auth_code' => $authorization_code,
                'amount' => $amount,
                'last4' => $last4,
                'exp_month'=> $exp_month,
                'exp_year' => $exp_year,
                'card_type' => $card_type,
                'bank' => $bank,
                'country_code'=> $country_code
            ]);
        }else{
             DB::table('user_auto_code')
             ->where('user_id',auth()->user()->id)
             ->update(
            [
                'auth_code' => $authorization_code,
                'amount' => $amount,
                'last4' => $last4,
                'exp_month'=> $exp_month,
                'exp_year' => $exp_year,
                'card_type' => $card_type,
                'bank' => $bank,
                'country_code'=> $country_code
            ]);
        }

        DB::table('transactions')->insert([

            'reference' => $reference,
            'amount' => $amount,
            'user_id' => auth()->user()->id,
            'status' => $status,
            'currency' => $currency,
            'paid_at' => $paid_at, 
            'remarks' => 'Fund wallet',
            'gateway' => 'Paystack',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //check for minimium bal 

        $get_bal = DB::table('user_wallet')->where('user_id',auth()->user()->id)->first();
        $min = MinimumBalance::Min;
        $newbal = $get_bal->balance + $get_bal->minimium_balance;

        if($get_bal->balance == 0.00 && $amount <  $min){
             DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
             'balance' => 0.00,
             'minimium_balance' => $get_bal->minimium_balance + $amount
             ]);

        }
        elseif($get_bal->balance == 0.00 && $amount >  $min){
             $min_bal = $amount - $min;
            //  $min_bal = $min - $get_bal->minimium_balance;
             DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
             'balance' => $min_bal + $get_bal->minimium_balance,
             'minimium_balance' => $min
             ]);
           
        }elseif($get_bal->minimium_balance == $min){
             $available_balance = $get_bal->balance + $amount;
             DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
             'balance' => $available_balance,
             ]);
        }
        elseif($amount =  $min){

            if($get_bal->minimium_balance < $min){
                 
                $min_bal = $amount - $min;
                DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
                'balance' => $get_bal->minimium_balance,
                'minimium_balance' => $min
                ]);

                } 
        }

        

        // if($get_bal->balance == 0.00){
        // DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
        //      'balance' => $amount,
        //      'minimium_balance' => 0.00
        //      ]);
        // }
        // elseif ($get_bal->balance > 0.00) {
        //     $newbal = $get_bal->balance + $amount;
        //     if($newbal < $min){
        //          DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
        //          'balance' => $newbal,
        //          'minimium_balance' => 0.00
        //          ]);

        //     }elseif($newbal > $min){
        //          $newminbal = $newbal - $min;
        //          DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
        //          'balance' => $newminbal,
        //          'minimium_balance' => $min
        //          ]);
        //     }
           
        // }
        
        $exist_user_count = DB::table('user_statistics')->where('user_id',auth()->user()->id)->first();

        if(is_null($exist_user_count)){
        $r = new UserStatistics ();
        $r->user_id = $res->user->id;
        $r->funds_added_count = 1;
        $r->save();

        }else{
        $rfind = DB::table('user_statistics')->where('user_id',$exist_user_count->user_id)->increment('funds_added_count',1);
        }

        return $status;
    }

     public function renewalPayment($get_authcode){

        $getUser = DB::table('users')->where('id',$get_authcode->user_id)->first();
        $amount = $get_authcode->subscription_amount;
        $newamount = (int)$amount;
        $auth_code = $get_authcode->auth_code;
        $email = $getUser->email;
        $fields = [
            'email' => $email,
            'amount' => $newamount * 100,
            "authorization_code" => $auth_code,
        ];
        $data_string = http_build_query($fields);
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseUrl.'/transaction/charge_authorization',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$this->secretKey,
                    "Cache-Control: no-cache",
                    ),
            ));

            //execute post
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $ress_new = json_decode($response);
            $reference = $ress_new->data->reference;
            $amount = $ress_new->data->amount/100;
            $status = $ress_new->data->status;
            $currency = $ress_new->data->currency;

            //populate the transaction table
            DB::table('transactions')->insert([
                'reference' => $reference,
                'amount' => $amount,
                'user_id' => $getUser->id,
                'status' => $status,
                'currency' => $currency,
                'remarks' => 'Auto charge',
                'gateway' => 'Paystack',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $status;
     }


     public function transfernewPayment($recipient_code,$source,$amount,$reference,$reason){

        $fields = [
        "source" => $source,
        "reason" => $reason,
        "amount" => $amount * 100,
        "recipient" => $recipient_code,
        "reference" => $reference
        ];

        $data_string = http_build_query($fields);
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseUrl.'/transfer',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$this->secretKey,
                    "Cache-Control: no-cache",
                    ),
        ));

        $new_response = curl_exec($curl);
        $err = curl_error($curl);
        $ress_new = json_decode($new_response);

        //wallet cal here



        // add to transaction table
        
         DB::table('transactions')->insert([
                'reference' => $ress_new->data->reference,
                'amount' => $ress_new->data->amount,
                'user_id' => auth()->user()->id,
                'status' => 'pending',
                'currency' => $ress_new->data->currency,
                'remarks' => $ress_new->data->reason,
                'gateway' => 'Paystack',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


        // if otp paystack code is disabled 
        return $ress_new;
        // if otp paystack code is disabled 

        //Db transact
         $u = DB::transaction(function () use ($recipient_code,$source,$amount,$reference,$reason) {
             $fields = [
                "source" => $source,
                "reason" => $reason,
                "amount" => $amount * 100,
                "recipient" => $recipient_code,
                "reference" => $reference
                ];

               $data_string = http_build_query($fields);
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseUrl.'/transfer',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$this->secretKey,
                    "Cache-Control: no-cache",
                    ),
                ));

                $new_response = curl_exec($curl);
                $err = curl_error($curl);
                $ress_new = json_decode($new_response); 
                });
                return $u;    

        //end db transact
     }

     public function verifytransferMoney($verifyTransferRef){

        $curl = curl_init(); 
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl.'/transfer/verify/'.$verifyTransferRef,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$this->secretKey,
            "Cache-Control: no-cache",
            ),
        ));
        $new_response = curl_exec($curl);
        $err = curl_error($curl);
        $ress_new = json_decode($new_response);
        DB::table('transactions')
            ->where('user_id',auth()->user()->id)
            ->update([
                'reference' => $ress_new->data->reference,
                'amount' => $ress_new->data->amount,
                'user_id' => auth()->user()->id,
                'status' => $ress_new->data->status,
                'currency' => $ress_new->data->currency,
                'remarks' => $ress_new->data->reason,
                'gateway' => 'Paystack',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        return $ress_new;

     }

     public function banklist(){

        $curl = curl_init(); 
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl.'/bank',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$this->secretKey,
            "Cache-Control: no-cache",
            ),
        ));
        $new_response = curl_exec($curl);
        $err = curl_error($curl);
        $ress_new = json_decode($new_response);
        return $ress_new;
     }


     public function resolve_bank($account_number,$bank_code){

       $curl = curl_init();
       curl_setopt_array($curl, array(
        CURLOPT_URL => $this->baseUrl."/bank/resolve?account_number=".$account_number."&bank_code=".$bank_code,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$this->secretKey,
        "Cache-Control: no-cache",
        ),
    ));
    
        $new_response = curl_exec($curl);
        $err = curl_error($curl);
        $ress_new = json_decode($new_response);
        return $ress_new;

     }

     public function transferrecipient($account_number,$account_name,$bank_code,$currency){

        $fields = [
            'type' => "nuban",
            'name' => $account_name,
            'account_number' => $account_number,
            'bank_code' => $bank_code,
            'currency' => $currency
        ];

        $data_string = http_build_query($fields);
      
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.paystack.co/transferrecipient',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$this->secretKey,
                    "Cache-Control: no-cache",
                    ),
            ));

            //execute post
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $ress_new = json_decode($response);

            DB::table('transfer_recipient')
            ->insert([
                'recipient_code' => $ress_new->data->recipient_code,
                'user_id' => auth()->user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return $ress_new;
     }

     
}


