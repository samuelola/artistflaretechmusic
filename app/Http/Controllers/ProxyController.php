<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{

    public function image($filename){

        $response = Http::withHeaders([
            'X-APP-A-KEY' => env('APP_A_API_KEY')
        ])->post(config('app.website_storage_link') . '/api/secure/stream', [
            'file' => $filename
        ]);

        if ($response->failed()) {
            abort(404, 'Image not found');
        }

        return response($response->body(), 200, [
            'Content-Type' => $response->header('Content-Type'),
        ]);
    }

}