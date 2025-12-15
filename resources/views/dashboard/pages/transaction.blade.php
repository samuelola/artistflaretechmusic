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

  @include('dashboard.ping')
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">All Transactions</h6>

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
            <div class="row">
                 <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Transactions</h5>
          </div>
            <div class="card-body">
            <div class="table-responsive">
              <table class="table bordered-table mb-0">
                <thead>
                  <tr>
                    <th>Full Name</th>
                    <th scope="col">Gateway</th>
                    <th scope="col">Remarks</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Subscription</th>
                    <th scope="col">Reference</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col">Date</th>
                  </tr>
                </thead>
                <tbody id="data-tranxwrapper">
                     @include('dashboard.pages.tranxdata')
                </tbody>
              </table>
                <!-- <div class="text-center mt-8">
                        <button class="btn btn-primary-600 load-more-tranxdata"><i class="fa fa-refresh" id="myIcon"></i> Load More Data...</button>
                    </div>
                    <div class="auto-tranxload text-center" style="display: none;">
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
                </div> -->
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



