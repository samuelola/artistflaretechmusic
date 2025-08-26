<!DOCTYPE html>
<html>
<head lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscribe Mail</title>
</head>
<body>
    <h2>Hello {{ $user }},</h2>
    <p>Thank you for subscribing to <strong>{{ config('app.name') }}</strong>.  
    We’re excited to have you on board.</p>

    <p>Your Plan details:</p>
    <p>Plan Name : {{$sub}}</p>
    <p>Plan Amount : {{$currency}}{{$amount}}</p>
    <p>Plan Duration : {{$duration}}</p>
    <p>Plan Expires At : {{$expires_at}}</p>
    <p>Here’s what you can expect:</p>
    <p>- ✅ {{$artist}} Artist(s) <p> 
    <p>- ✅ {{$track}} Track(s)<p> 
    <p>- ✅ {{$product}} Product(s)<p>


    <p>Thank you,<br>{{ config('app.name') }}</p>
</body>
</html>


