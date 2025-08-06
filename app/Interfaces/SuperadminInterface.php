<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface SuperadminInterface{

    public function updatePass($data,$id);
    public function storeSub($data);
    public function storeUser($data);
}