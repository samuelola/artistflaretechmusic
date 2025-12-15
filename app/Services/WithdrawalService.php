<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Interfaces\WithdrawalServiceInterface;
use App\Models\CryptoWallet;


class WithdrawalService implements  WithdrawalServiceInterface{

    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.cryptoapis.key'); // store in .env
        $this->baseUrl = "https://rest.cryptoapis.io/v2/blockchain-tools";
    }

    public function withdrawCrypto(string $coin, float $amount, string $toAddress)
    {
        // Map coin to blockchain network
        $map = [
            'ETH'  => ['chain' => 'ethereum', 'network' => 'mainnet'],
            'USDT' => ['chain' => 'ethereum', 'network' => 'mainnet'], // ERC20 example
            'FLR'  => ['chain' => 'flare', 'network' => 'mainnet'],    // if supported
        ];

        if (!isset($map[$coin])) {
            throw new \Exception("Unsupported coin: $coin");
        }

        $chain = $map[$coin]['chain'];
        $network = $map[$coin]['network'];

        // Make API request
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey,
        ])->post("{$this->baseUrl}/{$chain}/{$network}/transactions/transfer", [
            "toAddress" => $toAddress,
            "amount"    => $amount,
            "fee"       => "0.00021",  // or estimate via API
            "callbackUrl" => route('withdrawal.callback'), // webhook to update status
        ]);

        if ($response->failed()) {
            throw new \Exception("Withdrawal API failed: " . $response->body());
        }

        return $response->json();
    }

    public function deduct($user,$coin,$amount){
         // get user bal
        $wallet_bal = CryptoWallet::select('balance')->where(['user_id'=>$user->id,'coin'=>$coin])->first();
        $deduct = bcsub($wallet_bal->balance, $amount, 8);
        //update bal 
        $ff = DB::table('crypto_wallets')
        ->where(['user_id' => $user->id, 'coin' => $coin])
        ->update(['balance' => $deduct]);

        $reu = DB::table('user_statistics')->where('user_id', $user->id)->update(
              [
                'coin_balance'=> $deduct,
                'upload_release' => 0,
                'funds_added_count' => 0,
                'invite_points' => 0,
                'wallet_topup' => 0,
                'account_creation' => 0,
                'sub_purchase' => 0,
                'login_count' => 0
              ]
           );

        if($ff){
          return true;
        }
    }
}