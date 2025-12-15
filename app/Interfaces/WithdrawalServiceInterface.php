<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface WithdrawalServiceInterface{

     public function withdrawCrypto(string $coin, float $amount, string $toAddress);
     public function deduct($user,$coin,$amount);
}