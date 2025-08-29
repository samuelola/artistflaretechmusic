<!DOCTYPE html>
<html>
<head>
    <title>Subscription Renewal</title>
</head>
<body>
    <h2>Hello {{ $user }},</h2>
    <p>Your <strong>{{ $sub }}</strong> for {{$duration}} has been renewed
       will expire on <strong>{{ $expires_at }}</strong>.</p>

    <p>Enjoy your subscription.</p>

    <p>Thank you,<br>{{ config('app.name') }}</p>
</body>
</html>