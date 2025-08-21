<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscribe Mail</title>
  </head>
  <body>
    <h4>
        Hello , {{ $user }}! 🎉
    </h4>
    <p>Thank you for subscribing to **{{ config('app.name') }}**.  
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
    <p>
    If you did not subscribe, please ignore this email.
    Thanks,<br>
    {{ config('app.name') }}
    </p>
  </body>
</html>


