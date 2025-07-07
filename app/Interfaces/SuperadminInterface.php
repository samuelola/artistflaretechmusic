<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Model;

interface SuperadminInterface{

    public function updatePass($data,$id);
}