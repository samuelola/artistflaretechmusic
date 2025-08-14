@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')

@include('sweetalert::alert')

@php
$curr = DB::table('currency')->where('code',$sub_details->currency)->first();
$basecurrSymbol = DB::table('currency')->where('code',$currencyExchangeRate->rate_symbol)->first();
$amount = $sub_details->subscription_amount/$currencyExchangeRate->rate;
@endphp

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
  <h6 class="fw-semibold mb-0">Checkout</h6>

</div>

        <div class="row">
                <div class="col-md-12">
                        @if(session('error'))
                            
                            <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        
                                        {!! session('error') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                </div>
        </div>

   
            <!--new row -->
               <div class="row gy-4">
                    <div class="col-lg-8">
                        <div class="shadow-7 p-0 radius-12 bg-base h-100 overflow-hidden">
                            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between py-12 px-20 border-bottom border-neutral-200">
                                <h6 class="mb-0 fw-bold text-lg">Subscription Plan Details</h6>
                                
                            </div>
                            <div class="card-body p-20">
                                <!--begining-->
                                  <div class="card-body p-20 d-flex flex-column gap-12">
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Subscription Name</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">{{$sub_details->subscription_name}}</span>
                                               
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Subscription Price</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$curr->symbol}}{{number_format($sub_details->subscription_amount ?? '', 2, '.', ',')}} / {{$basecurrSymbol->symbol ?? ''}}{{number_format($amount ?? '', 2, '.', ',')}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <div class="d-flex">
                                                   <p style="margin-left: 2px;">{{$sub_details->plan_info_text}}</p>
                                                </div>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Number of Artist(s)</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                  {{$sub_details->artist_no}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">No of Track(s)</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                  {{$sub_details->no_of_tracks}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">No of Product(s)</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                  {{$sub_details->no_of_products}}
                                                </span>
                                               
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Duration</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                  {{$sub_details->subscription_duration}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Track File Quality</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                  {{$sub_details->track_file_quality}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Subscription For</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    @php
                                                       $sub = json_decode($sub_details->subscription_for);
                                                    @endphp
                                                    @foreach($sub as $key=>$value)
                                                         {{$value}}
                                                    @endforeach
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Include Tax</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$sub_details->include_tax}}
                                                </span>
                                               
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                <!--ending-->
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-4" style="height: 300px;">
                        <div class="shadow-7 p-0 radius-12 bg-base overflow-hidden h-100">
                            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between py-12 px-20 border-bottom border-neutral-200">
                                <h6 class="mb-0 fw-bold text-lg">Checkout</h6>
                            </div>
                            <div class="card-body p-20">
                                  <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Price:</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$curr->symbol}}{{number_format($sub_details->subscription_amount ?? '', 2, '.', ',')}}
                                                </span>
                                               
                                            </div>
                                   </div>
                                   
                                   <div class="d-flex align-items-center justify-content-between gap-3">
                                            
                                       <p style="font-size: 12px;margin-top: 30px;">
                                        By completing your purchase, you agree to these
                                        <a href="#" style="color:#ce11e7;">Terms of Use.</a>
                                   </div>

                                   <div class="d-flex align-items-center justify-content-between gap-3">
                                            
                                       <!-- <a href="#" class="btn btn-primary text-sm btn-sm px-8 py-12 w-100 radius-8">
                                        <iconify-icon icon="streamline-sharp:padlock-square-1" width="24" height="24"></iconify-icon>
                                        Proceed</a> -->
                                        <form method="post" action="{{route('checkout_payment')}}">
                                             @csrf
                                              <input type="hidden" name="subc_id" value="{{$sub_details->id}}"/>
                                              <input type="hidden" name="user_id" value="{{auth()->user()->id}}"/>
                                              <button class=" w-100 btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2" style="padding-inline:6rem;" type="submit">
                                                    <iconify-icon icon="streamline-sharp:padlock-square-1" width="24" height="24"></iconify-icon>
                                                    Proceed
                                              </button>
                                        </form>
                                       
                                   </div>



                            </div>
                        </div>
                    </div>
               </div>
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

@endsection



