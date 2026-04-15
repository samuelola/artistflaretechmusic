<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestR2Controller extends Controller
{
    public function uploadTest()
    {
        // Storage::disk('r2')->put('test.txt', 'Hello from Laravel R2!');

        Storage::disk('r2')->put(
            'songs/music2.mp3',
            file_get_contents(public_path('music2.mp3'))
        );

        return response()->json([
            'message' => 'File uploaded successfully!'
        ]);
    }
}
