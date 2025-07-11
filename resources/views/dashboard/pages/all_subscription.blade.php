@extends('dashboard.index')
@section('title')
  SuperAdmin
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
  <h6 class="fw-semibold mb-0">All Subscription</h6>
  <!-- <ul class="d-flex align-items-center gap-2">
    <li class="fw-medium">
      <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
        Dashboard
      </a>
    </li>
    <li>-</li>
    <li class="fw-medium">AI</li>
  </ul> -->
</div>

   

    <div class="row gy-4 mt-1" style="margin-bottom: 87px;">
      <div class="col-xxl-6 col-xl-12">
        <div class="card h-100" style="
    padding-bottom: 40px;
">
          <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
              <h6 class="text-lg mb-0">Add Subscription</h6>
              
            </div>
             <!--start-->
             <div class="row gy-4 mt-3" id="data-wrapperallsub">
               
                 @include('dashboard.pages.dataallsub')
                  
            </div>
            <!-- Data Loader -->

    <!-- <div class="auto-loadallsub text-center mt-3" style="display: none;">

<svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"

    x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">

    <path fill="#000"

        d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">

        <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"

            from="0 50 50" to="360 50 50" repeatCount="indefinite" />

    </path>

</svg>

</div> -->

</div>
             <!--end start-->
          
          </div>
      </div>
    </div>
  </div>

@endsection



