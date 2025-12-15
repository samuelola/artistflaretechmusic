<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Interfaces\RenewalInterface;
use DB;

class AutoRenewalPaymentService implements RenewalInterface 
{

    public function renewalPayment($get_authcode){

        $config = config('services.paystack_secret_test_key');
        $key = "Bearer ".$config;
        $getUser = DB::table('users')->where('id',$get_authcode->user_id)->first();
        $amount = $get_authcode->subscription_amount;
        $newamount = (int)$amount;
        $auth_code = $get_authcode->auth_code;
        $email = $getUser->email;
        $url = "https://api.paystack.co/transaction/charge_authorization";
        $fields = [
            'email' => $email,
            'amount' => $newamount * 100,
            "authorization_code" => $auth_code,
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

}