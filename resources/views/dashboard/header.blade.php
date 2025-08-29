<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
  

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FlareTechMusic | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{asset('flare_logo2.png')}}" sizes="96x96" />
    <!-- <link rel="icon" type="image/svg+xml" href="flare_tech/favicon.svg" />
    <link rel="shortcut icon" href="flare_tech/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="flare_tech/apple-touch-icon.png" />
    <link rel="manifest" href="flare_tech/site.webmanifest" /> -->
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{asset('assets/css/remixicon.css')}}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/bootstrap.min.css')}}" />
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/apexcharts.css')}}" />
    <!-- Data Table css -->
    <!-- <link rel="stylesheet" href="{{asset('datatables.css')}}" /> -->
    <!-- Text Editor css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/editor-katex.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/lib/editor.atom-one-dark.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/lib/editor.quill.snow.css')}}" />
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/flatpickr.min.css')}}" />
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/full-calendar.css')}}" />
    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/jquery-jvectormap-2.0.5.css')}}" />
    <!-- Popup css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/magnific-popup.css')}}" />
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/slick.css')}}" />
    <!-- prism css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/prism.css')}}" />
    <!-- file upload css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/file-upload.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/css/lib/audioplayer.css')}}" />
    <!-- main css -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/music.css')}}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script type="text/javascript"src="https://cdn.jsdelivr.net/npm/amplitudejs@5.3.2/dist/amplitude.js"></script>
    
    <style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}


/* .input-wrapper {
      position: relative;
      display: inline-block;
    }

    .input-wrapper input {
      padding-right: 35px; 
      width: 250px;
      height: 36px;
    }

    .input-loader {
      position: absolute;
      right: 285px;
      top: 68%;
      transform: translateY(-50%);
      display: none;
      color: #333;
    } */

   .input-wrapper {
      position: relative;
      display: inline-block;
      width: 100%; /* makes it scale */
      max-width: 400px; /* optional: limit width on big screens */
    }

    .input-wrapper input {
      width: 100%;
      /* padding-right: 2.5rem; */
      height: 40px;
      font-size: 1rem;
      box-sizing: border-box;
    }

    .input-loader {
      position: absolute;
      right: 285px;
      top: 68%;
      transform: translateY(-50%);
      display: none;
      color: #333;
      font-size: 1.2rem; /* adjusts with rem for responsiveness */
      pointer-events: none; /* avoids blocking clicks in input */
    }

    /* 📱 Smaller screens: adjust size */
    @media (max-width: 480px) {
      .input-wrapper input {
        height: 36px;
        font-size: 0.9rem;
      }
      .input-loader {
        font-size: 1rem;
        right: 1.9rem;
      }
    }
</style>


    
  </head>
  <body></body>


  
</html>