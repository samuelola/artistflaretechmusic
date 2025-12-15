<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;


class QueueController extends Controller
{
    public function triggerQueue()
    {
      Artisan::call('queue:work', [
        '--once' => true,      // processes only one job per call
        '--tries' => 3,
        '--timeout' => 90,
      ]);

      return response()->json(['status' => 'job processed']);
    }


}
