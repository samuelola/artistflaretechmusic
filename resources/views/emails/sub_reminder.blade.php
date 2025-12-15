<!DOCTYPE html>
<html>
<head>
    <title>Expiration Reminder</title>
</head>
<body>
    <h2>Hello {{ $user }},</h2>
    <p>This is a friendly reminder that your <strong>{{ $sub }}</strong> for {{$duration}} 
       will expire on <strong>{{ $expires_at }}</strong>.</p>

    <p>Please take the necessary action before it expires.</p>

    <p>Thank you,<br>{{ config('app.name') }}</p>
</body>
</html>