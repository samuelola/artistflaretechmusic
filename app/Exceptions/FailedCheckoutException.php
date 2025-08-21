<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class FailedCheckoutException extends Exception
{
    // public function __construct()
    // {
    //     parent::__construct('Error in processing the checkout');
    // }

    

    public function render(Request $request) {
      
        return response()->view('errors.500', [
            'errorMessage' => $this->getMessage()
        ], 500);
    }
}
