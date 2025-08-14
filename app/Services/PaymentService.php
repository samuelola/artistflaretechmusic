<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\PaymentInterface;
use DB;

class PaymentService implements PaymentInterface
{
    public function initalizePayment($amount,$email,$user_id){
        
        $newamount = (int)$amount;
        $key = "Bearer sk_test_bd26d3bef795b1b0896128cc607ce244af635f69";
        $callback_url = route('paystack.payment_callback',$user_id);
        $url = "https://api.paystack.co/transaction/initialize";
        $fields = [
            'email' => $email,
            'amount' => $newamount * 100,
            'callback_url' => $callback_url
        ];
        $data_string = json_encode($fields);
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
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
                  'Content-Type: application/json',  
                  "Authorization: $key"
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
        $key = "Bearer sk_test_bd26d3bef795b1b0896128cc607ce244af635f69";
         // verify transaction 
        $curl = curl_init(); 
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.paystack.co/transaction/verify/'.$reference,
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
                'Content-Type: application/json',  
                "Authorization: $key"
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
        $min = 2000.00;
        if($get_bal->minimium_balance == 0.00){
          
           $new_amount_bal = $amount - $min;
           DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
            'balance' => $new_amount_bal,
            'minimium_balance' => $min
           ]);
           
        }elseif($get_bal->minimium_balance == $min){
             
             DB::table('user_wallet')->where('user_id',auth()->user()->id)->update([
             'balance' => $get_bal->balance + $amount,
             'minimium_balance' => $min
             ]);
        }
        
       

        return $status;
    }

    
 
}


