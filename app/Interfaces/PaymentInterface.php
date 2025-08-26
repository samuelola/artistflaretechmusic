<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface PaymentInterface{

    public function initalizePayment($amount,$email,$user_id);
    public function verifyPayment($reference,$user_id);
    public function renewalPayment($data);
    public function banklist();
    public function resolve_bank($account_number,$bank_code);
    public function transferrecipient($account_number,$account_name,$bank_code,$currency);
    public function transfernewPayment($recipient_code,$source,$amount,$reference,$reason);
    public function verifytransferMoney($verifyTransferRef);
}