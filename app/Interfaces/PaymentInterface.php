<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface PaymentInterface{

    public function initalizePayment($amount,$email,$user_id);
    public function verifyPayment($reference,$user_id);
}