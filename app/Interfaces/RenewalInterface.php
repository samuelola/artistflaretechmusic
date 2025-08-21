<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RenewalInterface{

    public function renewalPayment($data);
    
}