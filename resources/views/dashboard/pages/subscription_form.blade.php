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
  <h6 class="fw-semibold mb-0">Subscription</h6>
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
            <div class="row gy-3 mt-3">
              <div class="col-md-6">
                <label class="form-label">Subscription Name</label>
                <input type="text" name="#0" class="form-control" placeholder="Enter Subscription Name">
              </div>
              <div class="col-md-6">
                <label class="form-label">No. of Artists</label>
                <select class="form-control js-example-basic-single" style="width: 100% !important">
                     <option>Select No of Artist</option>
                     @foreach($num as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
              </div>
            </div> 

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
                <label class="form-label">Stock Keeping Unit</label>
                <input type="text" name="" class="form-control" placeholder="Enter Stock Keeping Unit">
              </div>
              <div class="col-md-6">
              <label class="form-label">No. of Tracks</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No of Tracks</option>
                     @foreach($num as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
              </div>
            </div> 
            
            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">No. of Products</label>
                <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No Products</option>
                     @foreach($num as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
              </div>
              <div class="col-md-6">
                 <label class="form-label">Max No. of Tracks per Products.</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No. of Tracks per Products.</option>
                     @foreach($number_of_trackproduct as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
              </div>
            </div> 


            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Max No. of Artists per Products.</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No. of Tracks per Products.</option>
                     @foreach($number_of_trackproduct as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
              </div>
              <div class="col-md-6">
                 <label class="form-label">Subscription for</label>
                 <select class="form-control js-example-basic-singlee" multiple="multiple" style="width: 100% !important">
                       <option selected="selected" value="Album">Album</option>
                       <option value="white">Single</option>
                       <option value="purple">Compilation Album</option>
                 </select>
              </div>
            </div> 

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Track File Quality</label>
                 <select class="form-control js-example-basic-singlee" multiple="multiple" style="width: 100% !important">
                      <option selected="selected" value="Stereo">Stereo</option>
                </select>
              </div>
              <div class="col-md-6">
                 <label class="form-label">Currency</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                        @foreach($currency as $val)
                        <option value="{{$val->symbol}}">{{$val->country}} {{$val->currency}}-{{$val->symbol}}</option>
                        @endforeach
                 </select>
              </div>
            </div>

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Subscription Amount</label>
                <input type="number" name="" class="form-control">
              </div>
              <div class="col-md-6">
                 <label class="form-label">Include Tax</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                 </select>
              </div>
            </div>


            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Plan Info Text</label>
                <input type="text" name="" class="form-control" placeholder="Enter Plan Info Text">
              </div>
              <div class="col-md-6">
                 <label class="form-label">Include Tax</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                 </select>
              </div>
            </div>


            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Subscription Duration</label>
                <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select Duration</option>
                     @foreach($subscription_duration as $val)
                     <option value="{{$val->duration}}">{{$val->duration}}</option>
                     @endforeach
                </select>
              </div>
              <div class="col-md-6">
                 <label class="form-label">Subscription Limit per year</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select Limit per year</option>
                     @foreach($subscription_limit as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
              </div>
            </div> 


            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Subscription Duration</label>
                <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select Duration</option>
                     @foreach($subscription_duration as $val)
                     <option value="{{$val->duration}}">{{$val->duration}}</option>
                     @endforeach
                </select>
              </div>
              <div class="col-md-6">
                 <label class="form-label">Subscription Limit per year</label>
                 <select class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select Limit per year</option>
                     @foreach($subscription_limit as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
              </div>
            </div> 

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Is this free Subscription</label><br/>
              <label class="switch">
                <input type="checkbox" id="mySwitch1" value="0">
                <span class="slider round"></span>
              </label>
              <input type="hidden" name="free_sub" id="free_sub" value=""/>
              </div>
              <div class="col-md-6">
                 <label class="form-label">Is cancellation enable</label><br/>
                 <label class="switch">
                    <input type="checkbox" id="mySwitch2" value="0">
                    <span class="slider round"></span>
                  </label>
                  <input type="hidden" name="cancellation" id="cancellation"  value=""/>
              </div>
            </div> 

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">One Time Subscription</label><br/>
              <label class="switch">
                <input type="checkbox" id="mySwitch3" value="0">
                <span class="slider round"></span>
              </label>
                 <input type="hidden" name="one_time" id="one_time"  value=""/>
              </div>
              <div class="col-md-6">
                 <label class="form-label">Display Color</label><br/>
                 <input type="color" name="" value="#700070" class="form-control" style="width: 100px;">
              </div>
            </div> 


          </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
  <script>
        $(document).ready(function() {
          $("#mySwitch1").on("change", function() {
            if ($(this).is(":checked")) {
              var switchValue = 1;
              var r = $("#free_sub").val(switchValue);
              
            } else {
              var switchValue = $(this).val();
              var r = $("#free_sub").val(switchValue);
            }
          });

        });
  </script>
  <script>
        $(document).ready(function() {
          $("#mySwitch2").on("change", function() {
            if ($(this).is(":checked")) {
              var switchValue = 1;
              var r = $("#cancellation").val(switchValue);
              
            } else {
              var switchValue = $(this).val();
              var r = $("#cancellation").val(switchValue);
            }
          });

        });
  </script>
  <script>
        $(document).ready(function() {
          $("#mySwitch3").on("change", function() {
            if ($(this).is(":checked")) {
              var switchValue = 1;
              var r = $("#one_time").val(switchValue);
              
            } else {
              var switchValue = $(this).val();
              var r = $("#one_time").val(switchValue);
            }
          });

        });
  </script>
@endsection

