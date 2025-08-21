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
          <form method="post" action="{{route('add_subscription')}}">  
             @csrf
            <div class="row gy-3 mt-3">
              <div class="col-md-6">
                <label class="form-label">Subscription Name</label>
                <input type="text" value="{{ old('subscription_name') }}" name="subscription_name" class="form-control" placeholder="Enter Subscription Name">
                @error('subscription_name')
                  <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">No. of Artists</label>
                <select name="artist_no" class="form-control js-example-basic-single" style="width: 100% !important">
                     <option>Select No of Artist</option>
                     @foreach($num as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                     
                </select>
                @error('artist_no')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div> 

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
                <label class="form-label">Stock Keeping Unit</label>
                <input value="{{ old('stock_keeping_unit') }}" type="number" name="stock_keeping_unit" class="form-control" placeholder="Enter Stock Keeping Unit">
                @error('stock_keeping_unit')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="col-md-6">
              <label class="form-label">No. of Tracks</label>
                 <select name="no_of_tracks" class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No of Tracks</option>
                     @foreach($num as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
                @error('no_of_tracks')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div> 
            
            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">No. of Products</label>
                <select name="no_of_products" class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No Products</option>
                     @foreach($num as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
                @error('no_of_products')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="col-md-6">
                 <label class="form-label">Max No. of Tracks per Products.</label>
                 <select name="max_no_of_tracks_per_products" class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No. of Tracks per Products.</option>
                     @foreach($number_of_trackproduct as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
                @error('max no_of_tracks_per_products')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div> 


            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Max No. of Artists per Products.</label>
                 <select name="max_no_of_artists_per_products" class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select No. of Tracks per Products.</option>
                     @foreach($number_of_trackproduct as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
                @error('max_no_of_artists_per_products')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="col-md-6">
                 <label class="form-label">Subscription for</label>
                 <select name="subscription_for[]" class="form-control js-example-basic-singlee" multiple="multiple" style="width: 100% !important">
                       <option value="Album">Album</option>
                       <option value="Single">Single</option>
                       <option value="Compilation Album">Compilation Album</option>
                       <option value="EP">EP</option>
                 </select>
                 @error('subscription_for')
                  <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
              </div>
            </div> 

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Track File Quality</label>
                 <select name="track_file_quality" class="form-control js-example-basic-singlee" multiple="multiple" style="width: 100% !important">
                      <option value="Stereo">Stereo</option>
                </select>
                @error('track_file_quality')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="col-md-6">
                 <label class="form-label">Currency</label>
                 <select name="currency" class="form-control js-example-basic-singlee" style="width: 100% !important">
                        @foreach($currency as $val)
                        <option value="{{$val->code}}">{{$val->country}} {{$val->currency}}-{{$val->symbol}}</option>
                        @endforeach
                 </select>
                 @error('currency')
                  <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
              </div>
            </div>

            <div class="row gy-3 mt-2">  
              <div class="col-md-6">
              <label class="form-label">Subscription Amount</label>
                <input value="{{ old('subscription_amount') }}" step=".01" type="number" name="subscription_amount" class="form-control">
                @error('subscription_amount')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="col-md-6">
              <label class="form-label">Plan Info Text</label>
                <input value="{{ old('plan_info_text') }}" type="text" name="plan_info_text" class="form-control" placeholder="Enter Plan Info Text">
                @error('plan_info_text')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
            </div>


            <div class="row gy-3 mt-2">  
              
              <div class="col-md-6">
                 <label class="form-label">Include Tax</label>
                 <select name="include_tax" class="form-control js-example-basic-singlee" style="width: 100% !important">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                 </select>
                 @error('include_tax')
                  <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Subscription Duration</label>
                <select name="subscription_duration" class="form-control js-example-basic-singlee" style="width: 100% !important">

                     @foreach($subscription_duration as $val)
                     <option value="{{$val->duration}}">{{$val->duration}}</option>
                     @endforeach
                </select>
                @error('subscription_duration')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>

            </div>


            <div class="row gy-3 mt-2">  
              
              <div class="col-md-6">
                 <label class="form-label">Subscription Limit per year</label>
                 <select name="subscription_limit_per_year" class="form-control js-example-basic-singlee" style="width: 100% !important">
                     
                     @foreach($subscription_limit as $val)
                     <option value="{{$val->the_number}}">{{$val->the_number}}</option>
                     @endforeach
                </select>
                @error('subscription_limit_per_year')
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Is this free Subscription</label><br/>
                <label class="switch">
                  <input type="checkbox" id="mySwitch1" value="0">
                  <span class="slider round"></span>
                </label>
                <input type="hidden" name="is_this_free_subscription" id="free_sub" value="0"/>
                
              </div>

              <!-- <div class="col-md-6">
              <label class="form-label">Subscription Duration</label>
                <select name="sub_duration" class="form-control js-example-basic-singlee" style="width: 100% !important">
                     <option>Select Duration</option>
                     @foreach($subscription_duration as $val)
                     <option value="{{$val->duration}}">{{$val->duration}}</option>
                     @endforeach
                </select>
              </div> -->

            </div> 


             

            <div class="row gy-3 mt-2">  
              
              <div class="col-md-6">
                 <label class="form-label">Is cancellation enable</label><br/>
                 <label class="switch">
                    <input type="checkbox" id="mySwitch2" value="0">
                    <span class="slider round"></span>
                  </label>
                  <input type="hidden" name="is_cancellation_enable" id="cancellation"  value="0"/>
              </div>
              <div class="col-md-6">
                <label class="form-label">One Time Subscription</label><br/>
                <label class="switch">
                  <input type="checkbox" id="mySwitch3" value="0">
                  <span class="slider round"></span>
                </label>
                  <input type="hidden" name="is_one_time_subscription" id="one_time"  value="0"/>
                </div>
            </div> 

            <div class="row gy-3 mt-2">  
              
              <div class="col-md-6">
                 <label class="form-label">Display Color</label><br/>
                 <input type="color" name="display_color" value="#700070" class="form-control" style="width: 100px;">
              </div>
            </div> 

            <div class="row gy-3 mt-2">  
              
              <div class="col-md-6">
              <button type="submit" class="btn btn-primary-600 radius-8 px-20 py-11">Submit</button>
              </div>
            </div> 

            

             </form>
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

