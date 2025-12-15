<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $track->title }} | Music Player</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        .music-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            padding: 2rem;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }

        .music-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.5);
        }

        .music-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
        }

        .music-icon i {
            font-size: 2rem;
            color: white;
        }

        audio {
            width: 100%;
            outline: none;
            border-radius: 10px;
        }

        h3, h6 {
            color: #fff;
        }

        .artist-name {
            font-size: 0.95rem;
            color: #cfcfcf;
        }

        .btn-share {
            border: none;
            color: #fff;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: 0.3s;
        }

        .btn-share:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }
    </style>
  </head>

  <body>

    <div class="music-card text-center">
        <div class="music-icon">
            <i class="bi bi-music-note-beamed"></i>
        </div>

        <h3 class="fw-bold mb-1">{{ $track->title ?? '' }}</h3>
        <p class="artist-name mb-4">By {{ $track->artist ?? 'Unknown Artist' }}</p>

        <audio controls>
            <source src="{{ config('services.external_url.website2') }}/storage/{{ $track->audioFile->path }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        {{--<div class="mt-4">
            <button class="btn-share" onclick="navigator.clipboard.writeText(window.location.href)">
                <i class="bi bi-share-fill me-1"></i> Share
            </button>
        </div>--}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
