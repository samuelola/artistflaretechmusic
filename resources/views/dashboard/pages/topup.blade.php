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
                        @endif
                </div>
        </div>

   
            <!--new row -->
               <div class="row gy-4">
                <div class="col-xxl-12 col-lg-3"></div>
                <div class="col-xxl-12 col-lg-6">
                    <div class="card">
                        <!-- <div class="card-header flex-wrap align-items-center  gap-3" style="display:flex">
                            <h6 class="card-title mb-0">Topup  with</h6>
                            <img style="width: 150px;" src="{{asset('paystack_button.png')}}"/>
                        </div> -->
                        <div class="card-header">
                            <h6 class="card-title mb-0">Topup</h6>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3">
                                <form method="post" action="{{route('savetopup')}}">
                                    @csrf
                                    <div class="col-12">
                                    <label class="form-label">Enter Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" value="{{auth()->user()->email}}" class="form-control">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary-600">
                                          Topup
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-12 col-lg-3"></div>
            </div>
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

@endsection



