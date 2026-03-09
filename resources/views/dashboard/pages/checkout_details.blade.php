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

  @include('dashboard.ping')
  
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
                                                <span class="">{{$sub_details->subscription_name ?? ''}}</span>
                                               
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
                                                   <p style="margin-left: 2px;">{{$sub_details->plan_info_text ?? ''}}</p>
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
                                                  {{$sub_details->artist_no ?? ''}}
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
                                                  {{$sub_details->no_of_tracks ?? ''}}
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
                                                  {{$sub_details->no_of_products ?? ''}}
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
                                                  {{$sub_details->subscription_duration ?? ''}}
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
                                                  {{$sub_details->track_file_quality ?? ''}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <!--<div class="d-flex align-items-center justify-content-between gap-3">
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
                                        </div>-->

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Uploads</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$sub_details->uploads ?? ''}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Synced lyrics in stores</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$sub_details->synced_lyrics ?? ''}}
                                                </span>
                                               
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Customizable release date</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$sub_details->custom_release_date ?? ''}}
                                                </span>
                                               
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Customizable label name</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$sub_details->custom_release_label ?? ''}}
                                                </span>
                                               
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Split Sheet</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$sub_details->split_sheet ?? ''}}
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
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Vat:</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="">
                                                    {{$vat_value->vat}}%
                                                </span>
                                               
                                            </div>
    
                                   </div>

                                   @php
                                    $vat_percent = $vat_value->vat/100;
                                    $total_am = $sub_details->subscription_amount*$vat_percent;
                                    $total_amt = $sub_details->subscription_amount + $total_am;
                                   @endphp

                                   <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Total:</h6>
                                                   
                                                </div>
                                            </div>
                                            <div class="text-end d-flex gap-1 justify-content-end flex-column">
                                                <span class="" style="margin-right: 3px;">
                                                    {{$curr->symbol}}{{number_format($total_amt ?? '', 2, '.', ',')}}
                                                </span>
                                               
                                            </div>
    
                                   </div>
                                   
                                   <div class="d-flex align-items-center justify-content-between gap-3">
                                            
                                       <p style="font-size: 12px;margin-top: 30px;">
                                        By completing your purchase, you agree to these
                                        <a href="#" style="color:#ce11e7;">Terms of Use.</a>
                                   </div>

                                   <div class="d-flex align-items-center justify-content-between gap-3">

                                              <button class=" w-100 btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2" style="padding-inline:6rem;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                    <iconify-icon icon="tdesign:money-filled" width="24" height="24"></iconify-icon>
                                                    Proceed
                                              </button>
                                            
                                       <!-- <a href="#" class="btn btn-primary text-sm btn-sm px-8 py-12 w-100 radius-8">
                                        <iconify-icon icon="streamline-sharp:padlock-square-1" width="24" height="24"></iconify-icon>
                                        Proceed</a> -->

                                        <!-- <form method="post" action="{{route('checkout_payment')}}">
                                             @csrf
                                              <input type="hidden" name="subc_id" value="{{$sub_details->id}}"/>
                                              <input type="hidden" name="user_id" value="{{auth()->user()->id}}"/>
                                              <button class=" w-100 btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2" style="padding-inline:6rem;" type="submit">
                                                    <iconify-icon icon="tdesign:money-filled" width="24" height="24"></iconify-icon>
                                                    Proceed
                                              </button>
                                        </form> -->
                                       
                                   </div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Choose Method of Payment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="card-body p-24 pt-10">
              @php 
                 $wallet_bal = \DB::table('user_wallet')->where('user_id',auth()->user()->id)->first();
                 $available_bal = $wallet_bal->balance;
                 
                 $coins = \DB::table('user_statistics')->where('user_id',auth()->user()->id)
                    ->select(
                        'coin_balance',
                        'invite_members',
                        'upload_release',
                        'funds_added_count',
                        'invite_points',
                        'wallet_topup',
                        'account_creation',
                        // 'sub_purchase'
                        )
                    ->first();
                 $total_coinss = (int) array_sum((array) $coins);
                 $coins = \DB::table('settings')->where('setting_name','FlareProCoin')->first();
                 $equivalent_amount = $total_coinss * $coins->ngn;
              @endphp
                    <ul class="nav button-tab nav-pills mb-16" id="pills-tab-four" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10 active" id="pills-button-icon-home-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-home" type="button" role="tab" aria-controls="pills-button-icon-home" aria-selected="true">
                            <iconify-icon icon="iconoir:wallet-solid" width="24" height="24"></iconify-icon>
                            <span class="line-height-1">Wallet Option</span>
                          </button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10" id="pills-button-icon-details-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-details" type="button" role="tab" aria-controls="pills-button-icon-details" aria-selected="false" tabindex="-1">
                            <iconify-icon icon="system-uicons:coins" width="21" height="21"></iconify-icon>
                            <span class="line-height-1">FlarePro Coin Option</span>
                          </button>
                        </li>
                        
                    </ul>
                    <div class="tab-content" id="pills-tab-fourContent">
                        <div class="tab-pane fade show active" id="pills-button-icon-home" role="tabpanel" aria-labelledby="pills-button-icon-home-tab" tabindex="0">
                            <div class="d-flex align-items-center gap-3">
                                
                                <div class="flex-grow-1">
                                    <p style="color:#111827;">Available Balance :&#8358;
                                          @if($available_bal != 0)
                                              {{$available_bal}}
                                          @else
                                               {{$wallet_bal->minimium_balance}} 
                                          @endif 
                                    </P>
                                    <form method="post" action="{{route('checkout_payment')}}">
                                            @csrf
                                            <input type="hidden" name="total_amt" value="{{$total_amt}}"/>
                                            <input type="hidden" name="subc_id" value="{{$sub_details->id}}"/>
                                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}"/>
                                            <button class=" w-100 btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2" style="padding-inline:6rem;" type="submit">
                                                <iconify-icon icon="tdesign:money-filled" width="24" height="24"></iconify-icon>
                                                Proceed Wallet
                                            </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-button-icon-details" role="tabpanel" aria-labelledby="pills-button-icon-details-tab" tabindex="0">
                            <div class="d-flex align-items-center gap-3">
                               
                                <div class="flex-grow-1">
                                    <p style="color:#111827;margin-bottom: 3px;">Available Coins: {{$total_coinss ?? 0}} coins  (&#8358;<?php echo $equivalent_amount ?>)</P>
                                    <p style="color:#111827;">1 coin = &#8358;{{$coins->ngn}}</p>
                                    <form method="post" action="{{route('checkout_coin_payment')}}">
                                            @csrf
                                            <input type="hidden" name="subc_id" value="{{$sub_details->id}}"/>
                                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}"/>
                                            <button class=" w-100 btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2" style="padding-inline:6rem;" type="submit">
                                                <iconify-icon icon="system-uicons:coins" width="21" height="21"></iconify-icon>
                                                Proceed FlarePro Coin
                                            </button>
                                    </form>
                                   
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
         
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div> -->
    </div>
  </div>
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



