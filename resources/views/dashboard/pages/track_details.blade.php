@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')


@include('sweetalert::alert') 

<main class="dashboard-main">
  <div class="navbar-header">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search">
          <input type="text" name="search" placeholder="Search">
          <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
        </form>
      </div>
    </div>
    @include('dashboard.subheader')
  </div>
</div> 
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">Tracks Details</h6>
  
</div>

   
    <div class="card">
        <div class="card-body">
             <div class="row gy-4">

            
                <div class="col-lg-4">
                    <div class="card bg-soft-secondary" style="margin-top: 32px;">
                        <img src="{{asset('assets/images/card-component/walking3.jpg')}}" class="img-fluid w-100" alt="">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="d-flex align-items-top justify-content-between" style="margin-top: 30px;
    margin-bottom: 30px;">
                        <div class="music-detail">
                            <!-- <h3 style="font-size: 1.438rem !important;">{{$track_user_detail->TrackName}}</h3>
                            <span>{{$track_user_detail->Email}}</span><br>
                            <span>{{$track_user_detail->UserName}}</span> -->
                            <div class="d-flex align-items-center mb-4">
                            <h6 style="font-size: 20px !important;" class="mb-1 fw-bold me-3">{{$track_user_detail->ISRC}}&nbsp;<span>ISRC</span></h6>
                            <h6 style="font-size: 20px !important;" class="ps-3 fw-bold me-3 border-start"> 
                            <?php
                               $get_track_count = \DB::table('users')->where('email',$track_user_detail->Email)->sum('tracks');
                               echo $get_track_count;
                            ?>
                            &nbsp;<span>Tracks</span></h6>
                            <h6 style="font-size: 20px !important;" class="ps-3 fw-bold border-start">{{$track_user_detail->ReleaseCount}}&nbsp;<span>Release</span></h6>
                         </div>
                        
                            <div class="d-flex align-items-center">          
                              
                            <!--music player-->
                              <div id="single-song-player">
                                    <!-- <img data-amplitude-song-info="cover_art_url" /> -->
                                    <div class="bottom-container">
                                        <progress class="amplitude-song-played-progress" id="song-played-progress"></progress>

                                        <div class="time-container">
                                            <span class="current-time">
                                                <span class="amplitude-current-minutes"></span>:<span class="amplitude-current-seconds"></span>
                                            </span>
                                            <span class="duration">
                                                <span class="amplitude-duration-minutes"></span>:<span class="amplitude-duration-seconds"></span>
                                            </span>
                                        </div>

                                        <div class="control-container">
                                            <div class="amplitude-play-pause" id="play-pause"></div>
                                            <div class="meta-container">
                                                <span data-amplitude-song-info="name" class="song-name"></span>
                                                <span data-amplitude-song-info="artist"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--end music player-->
                            <!-- <a href="javascript:void(0);" class="btn btn-primary">Play music</a> -->
                            <!-- <a href="javascript:void(0);" class="btn btn-outline-secondary ms-3">Add Playlist</a> -->
                            </div>
                        </div>
                        <div class="music-right">
                            <div class="d-flex align-items-center flex-wrap">
                            <div class="iq-circle me-2 share"><a href="javascript:void();">
                                <svg style="color:#700084;" class="icon-20" width="18" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12S8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C19.66 8 21 6.66 21 5S19.66 2 18 2 15 3.34 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C4.34 9 3 10.34 3 12S4.34 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.34C15.11 18.55 15.08 18.77 15.08 19C15.08 20.61 16.39 21.91 18 21.91S20.92 20.61 20.92 19C20.92 17.39 19.61 16.08 18 16.08M18 4C18.55 4 19 4.45 19 5S18.55 6 18 6 17 5.55 17 5 17.45 4 18 4M6 13C5.45 13 5 12.55 5 12S5.45 11 6 11 7 11.45 7 12 6.55 13 6 13M18 20C17.45 20 17 19.55 17 19S17.45 18 18 18 19 18.45 19 19 18.55 20 18 20Z"></path>
                                </svg></a></div>
                            <div class="iq-circle me-2"><a href="javascript:void();">
                            <svg style="color:#700084;" class="icon-20" width="20" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z"></path>
                            </svg></a></div>                 
                            <div class="iq-circle">
                                <a href="javascript:void();">
                                <svg style="color:#700084;" class="icon-20" width="20" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M28.1562 19.1224V26.1224C28.1562 26.6528 27.9455 27.1615 27.5705 27.5366C27.1954 27.9116 26.6867 28.1224 26.1562 28.1224H6.15625C5.62582 28.1224 5.11711 27.9116 4.74204 27.5366C4.36696 27.1615 4.15625 26.6528 4.15625 26.1224V19.1224C4.15625 18.8571 4.26161 18.6028 4.44914 18.4153C4.63668 18.2277 4.89103 18.1224 5.15625 18.1224C5.42147 18.1224 5.67582 18.2277 5.86336 18.4153C6.05089 18.6028 6.15625 18.8571 6.15625 19.1224V26.1224H26.1562V19.1224C26.1562 18.8571 26.2616 18.6028 26.4491 18.4153C26.6367 18.2277 26.891 18.1224 27.1562 18.1224C27.4215 18.1224 27.6758 18.2277 27.8634 18.4153C28.0509 18.6028 28.1562 18.8571 28.1562 19.1224ZM15.4487 19.8299C15.5416 19.9228 15.6519 19.9966 15.7733 20.0469C15.8947 20.0972 16.0248 20.1231 16.1562 20.1231C16.2877 20.1231 16.4178 20.0972 16.5392 20.0469C16.6606 19.9966 16.7709 19.9228 16.8638 19.8299L21.8638 14.8299C21.9567 14.737 22.0304 14.6266 22.0806 14.5053C22.1309 14.3839 22.1568 14.2538 22.1568 14.1224C22.1568 13.991 22.1309 13.8609 22.0806 13.7395C22.0304 13.6181 21.9567 13.5078 21.8638 13.4149C21.7708 13.3219 21.6605 13.2482 21.5391 13.198C21.4178 13.1477 21.2876 13.1218 21.1562 13.1218C21.0249 13.1218 20.8947 13.1477 20.7734 13.198C20.652 13.2482 20.5417 13.3219 20.4487 13.4149L17.1562 16.7086V5.12236C17.1562 4.85714 17.0509 4.60279 16.8634 4.41525C16.6758 4.22772 16.4215 4.12236 16.1562 4.12236C15.891 4.12236 15.6367 4.22772 15.4491 4.41525C15.2616 4.60279 15.1562 4.85714 15.1562 5.12236V16.7086L11.8638 13.4149C11.6761 13.2272 11.4216 13.1218 11.1562 13.1218C10.8909 13.1218 10.6364 13.2272 10.4487 13.4149C10.2611 13.6025 10.1557 13.857 10.1557 14.1224C10.1557 14.3877 10.2611 14.6422 10.4487 14.8299L15.4487 19.8299Z" fill="currentColor"></path>
                                </svg></a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
         
             
           </div>
        </div>
    </div>

    

@endsection

@section('script')
  <script>
    Amplitude.init({
  bindings: {
    37: "prev",
    39: "next",
    32: "play_pause",

  },
  songs: [
    {
      name: '{{$track_user_detail->TrackName}}',
      artist: '{{$track_user_detail->UserName}}',
      // album: "We Are to Answer",
      //   url: "https://521dimensions.com/song/Ancient Astronauts - Risin' High (feat Raashan Ahmad).mp3",
      url: "../../904637.flac",
      cover_art_url:
        "https://521dimensions.com/img/open-source/amplitudejs/album-art/we-are-to-answer.jpg",
    },
  ],
});

window.onkeydown = function (e) {
  return !(e.keyCode == 32);
};

/*
    Handles a click on the song played progress bar.
  */
document
  .getElementById("song-played-progress")
  .addEventListener("click", function (e) {
    var offset = this.getBoundingClientRect();
    var x = e.pageX - offset.left;

    Amplitude.setSongPlayedPercentage(
      (parseFloat(x) / parseFloat(this.offsetWidth)) * 100
    );
  });

  </script>
@endsection

