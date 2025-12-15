<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CheckoutInterface{

    public function checkforwallet();
    public function checkwalletPayment($checkwallet);
    public function checktotalbalance($total_balance,$sub_detail,$total_amt='');
    public function checkformainbalance();
    public function minimumTransferAmount();
    public function checkforTransfer($amount);
}