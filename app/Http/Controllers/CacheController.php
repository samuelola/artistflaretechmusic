<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;


class CacheController extends Controller
{
    public function clear()
    {
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      Artisan::call('route:clear');
      Artisan::call('view:clear');

      return "cleared";
    
    }

    public function clearer(){

        return "helloddd";
    }


}
