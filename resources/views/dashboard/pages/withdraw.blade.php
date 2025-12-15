@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')

@include('sweetalert::alert')

 <style>
   #address::placeholder {
  color: #aaa; /* lighter color */
  opacity: 1;  /* keep the color as is */
}
 </style>

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
  <!-- <h6 class="fw-semibold mb-0">All Subscriptions</h6> -->

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
                        @elseif(session('success'))  
                            <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        {!! session('success') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>  
                        @endif
                </div>
                
        </div>

   
            <!--new row -->
               <div class="row gy-4 mt-3">
                
                <div class="col-xxl-12">
            <div class="card p-0 overflow-hidden position-relative radius-12 h-100">
                <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                    <h6 class="text-lg mb-0">Withdraw</h6>
                </div>
                <div class="card-body p-24 pt-10">
                    <ul class="nav button-tab nav-pills mb-16" id="pills-tab-four" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10 active" id="pills-button-icon-home-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-home" type="button" role="tab" aria-controls="pills-button-icon-home" aria-selected="false" tabindex="-1">
                            <iconify-icon icon="mdi-light:bank" width="24" height="24"></iconify-icon>
                            <span class="line-height-1">To Crypto Wallet</span>
                          </button>
                        </li>
                        <!--<li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10" id="pills-button-icon-details-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-details" type="button" role="tab" aria-controls="pills-button-icon-details" aria-selected="false" tabindex="-1">
                            <iconify-icon icon="mingcute:wallet-line" width="24" height="24"></iconify-icon>
                            <span class="line-height-1">To Another Wallet</span>
                          </button>
                        </li>

                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10" id="pills-button-icon-coins-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-coins" type="button" role="tab" aria-controls="pills-button-icon-coins" aria-selected="false" tabindex="-1">
                            <iconify-icon icon="mingcute:wallet-line" width="24" height="24"></iconify-icon>
                            <span class="line-height-1">FlareProCoins</span>
                          </button>
                        </li>-->
                        
                    </ul>
                    <div class="tab-content" id="pills-tab-fourContent">
                        <div class="tab-pane fade active show" id="pills-button-icon-home" role="tabpanel" aria-labelledby="pills-button-icon-home-tab" tabindex="0">
                            <div class="align-items-center gap-3">
                              
  
                                 <!--put form here-->
                                 <div class="row">

                                      <div class="col-md-3"></div>
                                      <div class="col-md-6">
                                        <!--<span class="text-yellow-500  mr-2">💡
                                        Send Money to Bank
                                      </span>-->
                                            <form style="margin-top:10px;" method="post" action="{{route('withdraw_store')}}">
                                                @csrf
                                                <div class="col-12">
                                                <label class="form-label">Enter Amount</label>
                                                <input type="number" name="amount" min="1" class="form-control" value="{{ old('amount') }}">
                                                @error('amount')
                                                        <p class="text-red-500 text-sm" style="color:#d22f2f">{{ $message }}</p>
                                                @enderror
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <label class="form-label">Coin</label>
                                                    <select id="coin" name="coin" 
                                                            class="form-control js-example-basic-single @error('coin') is-invalid @enderror" 
                                                            style="width: 100% !important">
                                                        
                                                        @foreach($cryptocurrencies as $val)
                                                            <option value="{{ $val->symbol }}" {{ old('coin') == $val->symbol ? 'selected' : '' }}>
                                                                {{ $val->symbol }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('coin')
                                                        <p class="text-red-500 text-sm" style="color:#d22f2f">{{ $message }}</p>
                                                    @enderror
                                                   
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <label class="form-label">Wallet Address</label>
                                                    <input id="address" type="text" name="address"
                                                     class="form-control @error('address') is-invalid @enderror" 
                                                     value="{{ old('address') }}" placeholder="e.g 0x1234abcd5678ef9012abcd3456mnop7890ABCD">
                                                    @error('address')
                                                        <p class="text-red-500 text-sm" style="color:#d22f2f">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button type="submit" class="btn btn-primary-600">
                                                      Send
                                                    </button>
                                                </div>

                                            </form>
                                      </div>
                                      <div class="col-md-3"></div>
                                      
                                  </div>
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

@section('script')
   







@endsection



