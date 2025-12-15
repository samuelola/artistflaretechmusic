<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface WalletTransferInterface{

    public function walletTransfer($amount,$recipient);
    public function checkForMinTransferAmount($min,$amount);
    public function checkForMinTransferCoinAmount($amount);
}