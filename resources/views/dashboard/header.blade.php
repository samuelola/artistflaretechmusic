<!DOCTYPE html>
<html lang="en" data-theme="light">
  

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FlareTechMusic | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
   
    <link rel="icon" type="image/png" href="{{asset('flare_logo2.png')}}" sizes="96x96" />
  
    <link rel="stylesheet" href="{{asset('assets/css/remixicon.css')}}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/bootstrap.min.css')}}" />
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/apexcharts.css')}}" />
    <!-- Data Table css -->
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script type="text/javascript"src="https://cdn.jsdelivr.net/npm/amplitudejs@5.3.2/dist/amplitude.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>

    .status {
      display: none;
      /*padding: 12px 20px;*/
      padding: 0px 0px;
      border-radius: 8px;
      margin: 10px auto;
      font-weight: bold;
      text-align: center;
      width: 280px;
      transition: opacity 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .online { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }
    .offline { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
    .loading { background: #e0e7ff; color: #3730a3; border: 1px solid #6366f1; }

    /* Spinner */
    .spinner {
      width: 18px;
      height: 18px;
      border: 3px solid rgba(0,0,0,0.2);
      border-top: 3px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    /* Toast container */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      z-index: 1000;
    }
    .toast {
      padding: 12px 16px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      color: white;
      min-width: 220px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      animation: slideIn 0.4s ease, fadeOut 0.5s ease 3.5s forwards;
    }
    .toast.success { background: #10b981; }
    .toast.error { background: #ef4444; }
    @keyframes slideIn { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
    @keyframes fadeOut { to { opacity: 0; transform: translateX(100%); } }

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