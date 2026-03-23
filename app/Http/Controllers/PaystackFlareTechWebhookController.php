<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;


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
        // Update transaction, subscription, mark invoice paid, etc.
         if (!$reference) {
            return response()->json(['message' => 'No reference'], 400);
         }
         // Idempotency check
         $transaction = Transaction::where('reference', $reference)->first();
         $transaction->update(['webhook_status' => 'paid']);
        //  Log::info("Transaction {$payload['data']['reference']} verified via webhook.");

         //This helps debugging when Paystack retries.
         Log::info('Paystack webhook received', [
            'event' => $payload['event'] ?? null,
            'reference' => $payload['data']['reference'] ?? null,
         ]);

      }

      return response()->json(['status' => 'ok']);
   }

   
}
