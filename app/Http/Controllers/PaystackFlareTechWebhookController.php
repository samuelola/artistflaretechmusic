<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PaystackFlareTechWebhookController extends Controller
{
   public function handle(Request $request)
   {
      $signature = $request->header('x-paystack-signature');
      if (!$signature || $signature !== hash_hmac('sha512', $request->getContent(), config('paystack.secretKey'))) {
        abort(403, 'Invalid signature');
      }

      $payload = $request->all();
      $event = $payload['event'] ?? '';
      if ($payload['event'] === 'charge.success') {
        $reference = $payload['data']['reference'] ?? null;
        // Update subscription, mark invoice paid, etc.
      }

      return response()->json(['status' => 'ok']);
   }

   

    
    
}
