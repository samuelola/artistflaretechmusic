<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface WalletTransferInterface{

    public function walletTransfer($amount,$recipient);
    public function checkForMaxTransferAmount($min,$amount);
}