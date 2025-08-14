@extends('dashboard.index')
@section('title')
  SuperAdmin
@endsection
@section('content')




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

@if(session('success'))
                
                <!-- <div class="fade show alert alert-dismissible alert-danger bg-danger-600 text-white border-danger-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                    {!! session('error') !!}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div> -->
                <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            
                            {!! session('success') !!} 
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">
    <!-- {{Auth::user()->role_id}}  -->
    @php
      $role = \App\Models\Role::where('id',Auth::user()->role_id)->first();
      echo $role->name;
      $currencyExchangeRateNgn = DB::table('currency')->where('code','NGN')->first();
      $currencyExchangeRateUsd = DB::table('currency')->where('code','USD')->first();
      $exchange_bal = $getwall_bal->balance/$currencyExchangeRateNgn->rate;
      $exchange_minbal = $getwall_bal->minimium_balance/$currencyExchangeRateNgn->rate;
      $exchange_tolbal = $total_balance/$currencyExchangeRateNgn->rate;
    @endphp
    Dashboard</h6>
  
</div>

    <!--new -->
    
    <div class="row gy-4" style="margin-bottom:20px;">

            <!-- Card Start -->
            <div class="col-xxl-4 col-md-6">
              
                <div class="shadow-7 p-16 radius-12 bg-base">
                   <!-- <h6 class="mb-0 fw-bold text-lg">Wallet</h6> -->
                    <div class="row g-3">
                        <div class="col-sm-6 col-xs-6">
                            <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-warning-light">
                                <img src="assets/images/home-twelve/icons/booking-card-big-icon4.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <span class="d-block mt-12 text-neutral-800">Total Balance</span>
                                <span style="color:#111827;font-weight:600;font-size:17px;" class="d-block mt-12 text-neutral-800">{{$currencyExchangeRateNgn->symbol}}{{number_format($total_balance ?? 0,2,'.',',')}} / {{$currencyExchangeRateUsd->symbol}}{{number_format($exchange_tolbal ?? '', 2, '.', ',')}}</span>
                            </div>
                            
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-green-light">
                               <img src="assets/images/home-twelve/icons/booking-card-big-icon4.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <span class="d-block mt-12 text-neutral-800">Available Balance</span>
                                               
                                <span style="color:#111827;font-weight:600;font-size:17px;" class="d-block mt-12 text-neutral-800">{{$currencyExchangeRateNgn->symbol}}{{number_format($getwall_bal->balance ?? 0,2,'.',',')}} / {{$currencyExchangeRateUsd->symbol}}{{number_format($exchange_bal ?? '', 2, '.', ',')}}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-blue-light">
                                <!-- <img src="assets/images/home-twelve/icons/booking-card-big-icon1.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1"> -->
                                 <img src="assets/images/home-twelve/icons/booking-card-big-icon4.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <!-- <span class="w-44-px h-44-px radius-8 bg-white d-flex justify-content-center align-items-center">
                                    <img src="assets/images/home-twelve/icons/booking-card-icon1.png" alt="Icon">
                                </span> -->
                                <span class="d-block mt-12 text-neutral-800">Minimum Balance</span>
                                <span style="color:#111827;font-weight:600;font-size:17px;" class="d-block mt-12 text-neutral-800">{{$currencyExchangeRateNgn->symbol}}{{number_format($getwall_bal->minimium_balance ?? 0,2,'.',',')}} / {{$currencyExchangeRateUsd->symbol}}{{number_format($exchange_minbal ?? '', 2, '.', ',')}}</span>
                            </div>
                            <!-- <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-warning-light">
                                <img src="assets/images/home-twelve/icons/booking-card-big-icon4.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <span class="d-block mt-12 text-neutral-800">Minimium Balance</span>
                                <span style="color:#111827;font-weight:600;font-size:18px;" class="d-block mt-12 text-neutral-800">#14,000.00 / $75</span>
                            </div> -->
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="py-20 px-24 radius-8 position-relative z-1 h-100">
                              <div class="d-flex gap-16" style="position: relative;left: -17px;top: 30px;">
                                        <a href="{{route('topup')}}" style="font-size: 13px;" class="btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2 " type="submit">
                                            Top UP <iconify-icon icon="lsicon:top-outline" width="20" height="20"></iconify-icon>
                                        </a>
                                        <!-- <button style="font-size: 13px;" class="btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2 " type="submit">Top UP <iconify-icon icon="lsicon:top-outline" width="20" height="20"></iconify-icon> </button> -->
                                        <button style="font-size: 13px;" class="btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2" type="submit">Transfer <i class="ri-send-plane-fill"></i> </button>
                                    </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- Card End -->

            <!-- Semi Circle Gauge start -->
              <div class="col-xxl-4 col-md-6">
                
                <div class="shadow-7 p-16 radius-12 bg-base">
                 
                    <div class="row g-3">
                        <div class="col-sm-6 col-xs-6">
                            <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-blue-light">
                                <img src="assets/images/home-twelve/icons/booking-card-big-icon1.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <span class="d-block mt-12 text-neutral-800">Login Daily</span>
                                <span style="color:#111827;font-weight:600;font-size:17px;" class="d-block mt-12 text-neutral-800">+{{$login_count->login_count ?? 0}} coins</span>
                            </div>
                            
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-red-light">
                               <img src="assets/images/home-twelve/icons/booking-card-big-icon4.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <span class="d-block mt-12 text-neutral-800">Invite New Members</span>
                                               
                                <span style="color:#111827;font-weight:600;font-size:17px;" class="d-block mt-12 text-neutral-800">+0 coins</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-warning-light">
                                <img src="assets/images/home-twelve/icons/booking-card-big-icon4.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <span class="d-block mt-12 text-neutral-800">Upload a Release</span>
                                <span style="color:#111827;font-weight:600;font-size:17px;" class="d-block mt-12 text-neutral-800">+0 coins</span>
                            </div>
                           
                        </div>
                        <div class="col-sm-6 col-xs-6">
                              <div class="py-20 px-24 radius-8 position-relative z-1 h-100 bg-green-light">
                                <img src="assets/images/home-twelve/icons/booking-card-big-icon4.png" alt="Big Icon" class="position-absolute end-0 bottom-0 me-8 z-n1">
                                <span class="d-block mt-12 text-neutral-800">Add funds to wallet</span>
                                <span style="color:#111827;font-weight:600;font-size:17px;" class="d-block mt-12 text-neutral-800">+0 coins</span>
                              </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- Semi Circle Gauge End -->

            

        </div>

    

    <!--end of new-->


    <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">
      
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-2 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Total Subscription</p>
                <h6 class="mb-0">{{number_format($resultsub_count , 0 , '.' , ',')}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                <!-- <iconify-icon icon="fa-solid:award" class="text-white text-2xl mb-0"></iconify-icon> -->
                <iconify-icon icon="streamline-flex:subscription-cashflow" class="text-white text-2xl mb-0" width="1em" height="1em"></iconify-icon>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              
              @if($total_subscription_last_30days > 10)
              <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +{{$total_subscription_last_30days}}</span> 
              @else
              <span class="d-inline-flex align-items-center gap-1 text-danger-main"><iconify-icon icon="bxs:down-arrow" class="text-xs"></iconify-icon> {{$total_subscription_last_30days}}</span> 
              @endif
              Last 30 days subscription
            </p>
          </div>
        </div><!-- card end -->
      </div>
      @php 
        $user_role_artist = App\Enum\UserStatus::Artist;
        $user_role_admin = App\Enum\UserStatus::Admin;
        $user_role_superadmin = App\Enum\UserStatus::SuperAdmin;
      @endphp
      @if(Auth::user()->role_id == $user_role_artist)
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Albums</p>
                <h6 class="mb-0">{{number_format($total_albums_user , 0 , '.' , ',')}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
              
                <iconify-icon icon="f7:music-albums" width="1em" height="1em" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              
            </p>
          </div>
        </div><!-- card end -->
      </div>
      @endif
      @if(Auth::user()->role_id == $user_role_artist)
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Singles</p>
                <h6 class="mb-0">{{number_format($total_labelUser , 0 , '.' , ',')}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                
                <iconify-icon icon="pepicons-pencil:music-note-single-circle" width="1em" height="1em" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <!-- <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +$20,000</span> 
              Last 30 days income -->
            </p>
          </div>
        </div><!-- card end -->
      </div>
      @endif
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-5 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Tracks</p>
                <h6 class="mb-0">{{number_format($resulttrack_count , 0 , '.' , ',')}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center">
                
                <iconify-icon icon="octicon:issue-tracks-24" width="1em" height="1em" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <!-- <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +$5,000</span> 
              Last 30 days expense -->
            </p>
          </div>
        </div><!-- card end -->
      </div>
      
      
    </div>

    <div class="row gy-4 mt-1">
      <div class="col-md-12">
           <form>
                 @csrf
                 <div style="float:left">
                      <select id="filter_country" name="filter_country" class="form-select bg-base form-select-sm w-auto radius-8 js-example-basic-singlee">
                        <option>Country</option>
                        @foreach($thecountry  as $vall)
                        <option value="{{$vall->iso2}}">{{$vall->name}}</option>
                        @endforeach
                      </select>
                 </div>
                 <div style="float:left">
                      <select id="filter_language" name="filter_language" class="form-select bg-base form-select-sm w-auto radius-8 js-example-basic-singlee">
                        <option>Language</option>
                        @foreach($thelang  as $vall)
                        <option value="{{$vall->iso}}">{{$vall->name}}</option>
                        @endforeach
                      </select>
                 </div>
                  <div>
                      <select id="filter_name" name="filter_name" class="form-select bg-base form-select-sm w-auto radius-8 js-example-basic-singlee">
                        <option value="0">Year</option>
                        @foreach($theyear  as $vall)
                        <option value="{{$vall->year}}">{{$vall->year}}</option>
                        @endforeach
                      </select>
                      <!-- <button id="filter"  class="btn btn-primary btn-sm">Filter</button> -->
                      <button id="filter_reset"  class="btn btn-primary-600 btn-sm">Reset</button>
                  </div>
              </form>
      </div>
      <div class="col-xxl-6 col-xl-12">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
              <h6 class="text-lg mb-0">Album/Track Chart</h6>
              
              
            </div>
            
            <!-- <div id="chart" class="pt-28 apexcharts-tooltip-style-1"></div> -->
            <div id="chart1"></div>
            <div id="wollal"></div>
            <div id="wolla2"></div>
            <div id="wolla3"></div>
            
            
          </div>
        </div>
      </div>
      @if(Auth::user()->role_id == $user_role_artist)
      <div class="col-xxl-3 col-xl-6">
        <div class="card h-100 radius-8 border">
          <div class="card-body p-24">
              <h6 class="mb-12 fw-semibold text-lg mb-16">Users</h6>
              <div id="barChart" class="barChart"></div>
            
          </div>
        </div>
      </div>
      @endif

      <div class="col-xxl-3 col-xl-6">
        <div class="card h-100 radius-8 border-0 overflow-hidden">
          <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
              <h6 class="mb-2 fw-bold text-lg">Subscription</h6>
              
            </div>


            <div id="userOverviewDonutChart" class="apexcharts-tooltip-z-none"></div>

           
            
          </div>
        </div>
      </div>

      @if(Auth::user()->role_id == $user_role_superadmin && Auth::user()->role_id == $user_role_admin)
      <div class="col-xxl-9 col-xl-12">
        <div class="card h-100">
            <div class="card-body p-24">

              <div class="d-flex flex-wrap align-items-center gap-1 justify-content-between mb-16">
                <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center active" id="pills-to-do-list-tab" data-bs-toggle="pill" data-bs-target="#pills-to-do-list" type="button" role="tab" aria-controls="pills-to-do-list" aria-selected="true">
                      Latest Registered 
                      <span class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{number_format($users , 0 , '.' , ',')}}</span>
                    </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center" id="pills-recent-leads-tab" data-bs-toggle="pill" data-bs-target="#pills-recent-leads" type="button" role="tab" aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                      Latest Subscribers 
                      <span class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{number_format($total_subscription , 0 , '.' , ',')}}</span>
                    </button>
                  </li>
                </ul>
                <!-- <a href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                  View All
                  <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                </a> -->
              </div>

              <div class="tab-content" id="pills-tabContent">   
                <div class="tab-pane fade show active" id="pills-to-do-list" role="tabpanel" aria-labelledby="pills-to-do-list-tab" tabindex="0">
                
                  <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                      <thead>
                        <tr>
                          <th scope="col">Users</th>
                          <th scope="col">Registered On</th>
                          <th scope="col">Albums</th>
                          <th scope="col">Tracks</th>
                          <th scope="col">Language</th>
                          <th scope="col">Country</th>
                          <th scope="col">State</th>
                          <th scope="col" class="text-center">Status</th>
                          <th scope="col" class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody id="data-wrapperr">
                      
                          @include('dashboard.pages.dataa')
                        
                      </tbody>
                    </table>
                  </div>
                  
                  <div class="text-center mt-8">
                    <button class="btn btn-primary-600 load-more-dataa"><i class="fa fa-refresh" id="myIcon"></i> Load More Data...</button>
                </div>
                <div class="auto-loadd text-center" style="display: none;">
                        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#000"
                                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                            </path>
                        </svg>
                </div>
              

                </div>

                <div class="tab-pane fade" id="pills-recent-leads" role="tabpanel" aria-labelledby="pills-recent-leads-tab" tabindex="0">
                
                   <!-- table was removed here-->
                   <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                      <thead>
                        <tr>
                          <th scope="col">Subscribers </th>
                          <th scope="col">Payment Date</th>
                          <th scope="col">Invoice Number</th>
                          <th scope="col">Country</th>
                          <th scope="col">Mobile Number</th>
                          <th scope="col">Partner Name</th>
                          <th scope="col">Paid By</th>
                          <th scope="col">Plan Name</th>
                          <th scope="col">Amount Paid</th>
                          <th scope="col">Paid Through</th>
                          <th scope="col">Subscription Amount</th>
                          
                        </tr>
                      </thead>
                      <tbody id="data-wrapper">
                          @include('dashboard.pages.data')
                        
                      </tbody>
                      
                    </table>
                    
                     
                  </div>

                  <div class="text-center mt-8">
                    <button class="btn btn-primary-600 load-more-data"><i class="fa fa-refresh" id="myIcon"></i> Load More Data...</button>
                  </div>
                <div class="auto-load text-center" style="display: none;">
                        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#000"
                                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                            </path>
                        </svg>
                    </div>
                </div>

                   <!--end table here-->
                </div>
              </div>
          </div>
        </div>
      </div>
      @endif
      

      
    </div>
  </div>

@endsection

@section('script')
<script>
    var ENDPOINT = "{{ route('dashboard') }}";
    var page = 1;
  
    $(".load-more-dataaplan").click(function(){
        page++;
        infinteLoadMorerplan(page);
    });
    
  
    /*------------------------------------------
    --------------------------------------------
    call infinteLoadMore()
    --------------------------------------------
    --------------------------------------------*/
    function infinteLoadMorerplan(page) {
        $.ajax({
                url: ENDPOINT + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-loaddplan').show();
                }
            })
            .done(function (response) {

                console.log(response.newhtmlplan);
                if (response.newhtmlplan == '') {
                    $('.auto-loaddplan').html("We don't have more data to display :(");
                    return;
                }

                $('.auto-loaddplan').hide();
                $("#data-wrapperrplan").append(response.newhtmlplan);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    }
</script>


<script>
   // =========================== Sales Statistic Line Chart Start ===============================
   var options = {
    series: [
    {
      name: 'Albums',
      data: [
        <?php 
           foreach ($albumvalue  as $valuee) {
               echo "'$valuee',";
           }
        
        ?>
      ]
    },
    {
      name: 'Tracks',
      data: [
        <?php 
           foreach ($trackvalue  as $valuee) {
               echo "'$valuee',";
           }
        
        ?>
      ]
    }
  ],
    chart: {
      height: 350,
      type: 'line',
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        top: 6,
        left: 0,
        blur: 4,
        color: "#000",
        opacity: 0.1,
      },
    },
    colors: ["#FF1654", "#247BA0"], // Set color for series
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      colors: ["#FF1654", "#247BA0"], // Specify the line color here
      width: 3
    },
    markers: {
      size: 0,
      strokeWidth: 3,
      hover: {
        size: 8
      }
    },
    tooltip: {
      enabled: true,
      x: {
        show: true,
      },
      y: {
        show: false,
      },
      z: {
        show: false,
      }
    },
    grid: {
      row: {
        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
      borderColor: '#D1D5DB',
      strokeDashArray: 3,
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return value;
        },
        
        style: {
          fontSize: "14px"
        }
      },
    },
    xaxis: {
      categories: [
        <?php 
           foreach ($theyear  as $valuee) {
               echo "'$valuee->year',";
           }
        
        ?>
      ],
      tooltip: {
        enabled: false
      },
      labels: {
        formatter: function (value) {
          return value;
        },
        style: {
          fontSize: "14px"
        }
      },
      axisBorder: {
        show: false
      },
      crosshairs: {
        show: true,
        width: 20,
        stroke: {
          width: 0
        },
        fill: {
          type: 'solid',
          color: '#487FFF40',
          // gradient: {
          //   colorFrom: '#D8E3F0',
          //   // colorTo: '#BED1E6',
          //   stops: [0, 100],
          //   opacityFrom: 0.4,
          //   opacityTo: 0.5,
          // },
        }
      }
    }
  };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();
  // =========================== Sales Statistic Line Chart End ===============================
</script>



<script>
  $('#filter_country').on('change', function (e) {
       e.preventDefault();
       $('#chart1').hide();
       $('#wollal').hide();
       $('#wolla2').hide();
       var filter_country = $('#filter_country').val();
        $.ajax({
             
             headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: '{{route("filter_info")}}',
             method: 'GET',
             data: { filter_country_data: filter_country },
             success: function (res) {

                let fggcon = res.countrydata;
                let conyear = fggcon.map(a => a.year);
                let conalbums = fggcon.map(a => a.albums);
                let contracks = fggcon.map(a => a.tracks);
                console.log(fggcon);
                $('#wolla3')
                .empty()
                .append(
                  '<div id="newchartcountry"></div>'
                ) 

                var options = {
    series: [
    {
      name: 'Albums',
      data: conalbums
    },
    {
      name: 'Tracks',
      data: contracks
    }
  ],
    chart: {
      height: 350,
      type: 'line',
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        top: 6,
        left: 0,
        blur: 4,
        color: "#000",
        opacity: 0.1,
      },
    },
    colors: ["#FF1654", "#247BA0"], // Set color for series
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      colors: ["#FF1654", "#247BA0"], // Specify the line color here
      width: 3
    },
    markers: {
      size: 0,
      strokeWidth: 3,
      hover: {
        size: 8
      }
    },
    tooltip: {
      enabled: true,
      x: {
        show: true,
      },
      y: {
        show: false,
      },
      z: {
        show: false,
      }
    },
    grid: {
      row: {
        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
      borderColor: '#D1D5DB',
      strokeDashArray: 3,
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return value;
        },
       
        style: {
          fontSize: "14px"
        }
      },
    },
    xaxis: {
      categories: conyear,
      tooltip: {
        enabled: false
      },
      labels: {
        formatter: function (value) {
          return value;
        },
        style: {
          fontSize: "14px"
        }
      },
      axisBorder: {
        show: false
      },
      crosshairs: {
        show: true,
        width: 20,
        stroke: {
          width: 0
        },
        fill: {
          type: 'solid',
          color: '#487FFF40',
          
        }
      }
    }
  };

    var chart = new ApexCharts(document.querySelector("#newchartcountry"), options);
    chart.render();
                
             }

         });        
  });
  
</script>

<script>
  $('#filter_language').on('change', function (e) {
       e.preventDefault();
       $('#chart1').hide();
       $('#wollal').hide();
       var filter_language = $('#filter_language').val();
        $.ajax({
           
             headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: '{{route("filter_info")}}',
             method: 'GET',
             data: { filter_language_data: filter_language },
             success: function (res) {

                let fgg = res.langdata;
                let langyear = fgg.map(a => a.year);
                let langalbums = fgg.map(a => a.albums);
                let langthe_tracks = fgg.map(a => a.tracks);
                console.log(fgg);
                $('#wolla2')
                .empty()
                .append(
                  '<div id="newchartlang"></div>'
                ) 

                var options = {
    series: [
    {
      name: 'Albums',
      data: langalbums
    },
    {
      name: 'Tracks',
      data: langthe_tracks
    }
  ],
    chart: {
      height: 350,
      type: 'line',
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        top: 6,
        left: 0,
        blur: 4,
        color: "#000",
        opacity: 0.1,
      },
    },
    colors: ["#FF1654", "#247BA0"], // Set color for series
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      colors: ["#FF1654", "#247BA0"], // Specify the line color here
      width: 3
    },
    markers: {
      size: 0,
      strokeWidth: 3,
      hover: {
        size: 8
      }
    },
    tooltip: {
      enabled: true,
      x: {
        show: true,
      },
      y: {
        show: false,
      },
      z: {
        show: false,
      }
    },
    grid: {
      row: {
        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
      borderColor: '#D1D5DB',
      strokeDashArray: 3,
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return value;
        },
       
        style: {
          fontSize: "14px"
        }
      },
    },
    xaxis: {
      categories: langyear,
      tooltip: {
        enabled: false
      },
      labels: {
        formatter: function (value) {
          return value;
        },
        style: {
          fontSize: "14px"
        }
      },
      axisBorder: {
        show: false
      },
      crosshairs: {
        show: true,
        width: 20,
        stroke: {
          width: 0
        },
        fill: {
          type: 'solid',
          color: '#487FFF40',
          
        }
      }
    }
  };

    var chart = new ApexCharts(document.querySelector("#newchartlang"), options);
    chart.render();
                
             }

         });        
  });
  
</script>

<script>
   
   $('#filter_name').on('change', function (e) {
       
        e.preventDefault();
        let chart;
        $('#chart1').hide();
        $('#wollal').show();
        var filter_chart_data = $('#filter_name').val();
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{route("filter_info")}}',
            method: 'GET',
            data: { date_filter_data: filter_chart_data},
            cache: false,
            async: true,
            success: function (res) {

              if(res.data){

                console.log(res.data);
                var theyyear = res.theyyear;
                //console.log(theyyear);
                var fg = res.data;
                let the_year = fg.map(a => theyyear+"-"+a.month);
                let the_albums = fg.map(a => a.albums);
                let the_tracks = fg.map(a => a.tracks);

                if(res.nodata){
                   alert('no data');
                }else{
                    // add chart here
                   $('#wollal')
                   .empty()
                   .append(
                      '<div id="newchart1"></div>'
                   )   

                   var options = {
                          series: [
                          {
                            name: 'Albums',
                            data: the_albums
                          },
                          {
                            name: 'Tracks',
                            data: the_tracks
                          }
                        ],
                          chart: {
                            height: 350,
                            type: 'line',
                            toolbar: {
                              show: false
                            },
                            zoom: {
                              enabled: false
                            },
                            dropShadow: {
                              enabled: true,
                              top: 6,
                              left: 0,
                              blur: 4,
                              color: "#000",
                              opacity: 0.1,
                            },
                          },
                          colors: ["#FF1654", "#247BA0"], // Set color for series
                          dataLabels: {
                            enabled: false
                          },
                          stroke: {
                            curve: 'smooth',
                            colors: ["#FF1654", "#247BA0"], // Specify the line color here
                            width: 3
                          },
                          markers: {
                            size: 0,
                            strokeWidth: 3,
                            hover: {
                              size: 8
                            }
                          },
                          tooltip: {
                            enabled: true,
                            x: {
                              show: true,
                            },
                            y: {
                              show: false,
                            },
                            z: {
                              show: false,
                            }
                          },
                          grid: {
                            row: {
                              colors: ['transparent', 'transparent'],
                              opacity: 0.5
                            },
                            borderColor: '#D1D5DB',
                            strokeDashArray: 3,
                          },
                          yaxis: {
                            labels: {
                              formatter: function (value) {
                                return value;
                              },
                              
                              style: {
                                fontSize: "14px"
                              }
                            },
                          },
                          xaxis: {
                            categories: the_year,
                            tooltip: {
                              enabled: false
                            },
                            labels: {
                              formatter: function (value) {
                                return value;
                              },
                              style: {
                                fontSize: "14px"
                              }
                            },
                            axisBorder: {
                              show: false
                            },
                            crosshairs: {
                              show: true,
                              width: 20,
                              stroke: {
                                width: 0
                              },
                              fill: {
                                type: 'solid',
                                color: '#487FFF40',
                                
                              }
                            }
                          }
                        };

                      if (chart) {
                              chart.updateOptions(options);
                          } else {
                              chart = new ApexCharts(document.querySelector("#newchart1"), options);
                              chart.render();
                      }

                //endchart
                }
                
              }
             

            }
        });
    });
</script>

<script>
    $('#filter_reset').on('click', function (e) {
        e.preventDefault();
        location.reload();
        // $("#filter_name option").prop("selected", false).trigger( "change" );
        // $("#filter_language option").prop("selected", false).trigger( "change" );
    });
</script>


@endsection



