<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ValidAudioViaApi implements Rule
{
    protected $message = 'Invalid audio file.';
    
    public function passes($attribute, $value)
    {
        // Store temporarily so API can access it (assuming local -> public disk)
        $path = $value->store('temp', 'public');
        $fileUrl = asset('storage/' . $path);

        // Send request to Rendi
        $response = Http::withToken(config('services.rendi.key'))
            ->withoutVerifying()
            ->withOptions(["verify"=>false])
            ->post(config('services.rendi.url') . '/jobs', [
                'input' => $fileUrl,
                'output' => 'null', // we just need analysis, not output file
                'filters' => '-af silencedetect=noise=-30dB:d=20',
            ]);

        if ($response->failed()) {
            $this->message = "Could not analyze the audio file.";
            return false;
        }

        $data = $response->json();

        // Depending on API, logs may come in 'stderr' or 'analysis'
        $logs = $data['stderr'] ?? ($data['analysis'] ?? '');

        // Get duration via a separate probe call
        $duration = $this->getDuration($fileUrl);

        // Check silence longer than 20s
        if (strpos($logs, 'silence_start') !== false) {
            $this->message = "The {$attribute} contains silence longer than 20 seconds.";
            return false;
        }

        // Check abrupt ending
        $lastSoundEnd = $this->extractLastSilenceEnd($logs, $duration);
        if ($duration - $lastSoundEnd > 2) {
            $this->message = "The {$attribute} ends abruptly or has too much silence at the end.";
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }

    private function getDuration($fileUrl): float
    {
        $response = Http::withToken(config('services.rendi.key'))
            ->post(config('services.rendi.url') . '/probe', [
                'input' => $fileUrl
            ]);

        if ($response->failed()) return 0.0;

        return floatval($response->json()['format']['duration'] ?? 0.0);
    }

    private function extractLastSilenceEnd(string $logs, float $duration): float
    {
        preg_match_all('/silence_end: ([0-9\.]+)/', $logs, $matches);

        if (!empty($matches[1])) {
            return floatval(end($matches[1]));
        }

        return $duration;
    }
}
