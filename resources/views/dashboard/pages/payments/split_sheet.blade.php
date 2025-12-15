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
  <h6 class="fw-semibold mb-0">Add Split Sheet</h6>
  
</div>

   
    <div class="card h-100 p-0 radius-12">
       <div class="card-body p-24">
           <div class="row justify-content-center">
               <div class="col-xxl-6 col-xl-8 col-lg-10">
                    <div class="card border">
                        <div class="card-body">
                            <form action="" method="post">
                                @csrf
                                 <div class="row mb-3">
                                        <div class="col-md-6">

                                            <label class="form-label">First Name</label>
                                            <input type="text" name="first_name" class="form-control" placeholder="FirstName" value="{{ old('first_name') }}">
                                            @error('first_name')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror  
                                        </div> 
                                        <div class="col-md-6">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" placeholder="LastName" value="{{ old('last_name') }}">
                                            @error('last_name')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror  
                                        </div>
                                </div>
                                <div class="row mb-3">
                                  <div class="col-md-6">
                                      <label class="form-label">Email</label>
                                          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                          @error('email')
                                              <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                          @enderror 
                                          
                                      </div> 
                                      <div class="col-md-6">

                                          <label class="form-label" style="display: block;">Language</label>
                                          <select name="language" class="js-example-basic-single" style="width: 100% !important">
                                                  <option>Language</option>  
                                                    @foreach($languages as $vall) 
                                                      <option value="{{$vall->iso}}">{{$vall->name}}</option>
                                                    @endforeach
                                          </select>
                                          @error('language')
                                              <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                          @enderror 
                                          
                                      </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="position-relative ">

                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control generated-password" id="your-password" placeholder="Password">
                                        <span style="color:#4b5563;margin-top: 17px;" class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                        
                                        </div>
                                        <button style="margin-top:12px;" type="button" class="btn btn-sm btn-primary" id="generate-password-btn">Generate Password</button>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror 
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="position-relative ">
                                        <label class="form-label">Password Confirm</label>
                                        <input type="password" name="password_confirmation" class="form-control" id="your-passwordd" placeholder="Password">
                                        <span  style="color:#4b5563;margin-top:18px;margin-right: 13px !important;" class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-passwordd"></span>
                                        </div>
                                      
                                        @error('password_confirmation')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                            <div class="col-md-6">

                               <label class="form-label" style="display: block;">Country</label>
                                <select id="country-dropdown" data-width="100%" class="js-example-basic-single" name="country">
                                     <option>Country</option>  
                                        @foreach($all_countries as $val) 
                                        <option value="{{$val->iso2}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
                                @error('country')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror 
    
                            </div> 
                            <div class="col-md-6">

                                          <label class="form-label" style="display: block;">State</label>
                                            <select id="state-dropdown"  class="js-example-basic-single" name="state" style="width: 100% !important">
                                              <option>State</option>
                                            </select>
                                            
                                            @error('state')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror  
                                    </div>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <div class="form-check style-check d-flex align-items-start">
                                    <input type="checkbox" class="form-check-input border border-neutral-300 mt-4" name="terms"  value="1" id="condition">
                                    <label class="form-check-label text-sm" for="condition">
                                        By creating an account means you agree to the 
                                        <a style="color:#ce11e7 !important;" href="javascript:void(0)" class="text-primary-600 fw-semibold">Terms & Conditions</a> and our 
                                        <a style="color:#ce11e7 !important;" href="javascript:void(0)" class="text-primary-600 fw-semibold">Privacy Policy</a>
                                    </label>
                                    @error('terms')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror 
                                </div>
                                
                            </div>

                            <div class="d-flex align-items-center flex-wrap gap-28 mt-2">
                                  <div class="form-check checked-primary d-flex align-items-center" style="margin-top: 20px;">
                                    <p>Are you paying Tax</p>
                                    </div>
                                    <div class="form-check checked-primary d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="radio" name="pay_tax" id="radio11" value="1">
                                        <label style="color:#4b5563" class="form-check-label line-height-1 fw-medium text-secondary-light" for="radio11">Yes</label>
                                    </div>
                                    <div class="form-check checked-primary d-flex align-items-center">
                                        <input class="form-check-input" type="radio" name="pay_tax" id="radio12" value="0">
                                        <label style="color:#4b5563" class="form-check-label line-height-1 fw-medium text-secondary-light" for="radio11">No</label>

                                        <strong style="color:red">{{ $errors->first('pay_tax') }}</strong>
                                    </div>
                                    <!-- @error('pay_tax')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror -->
                                  
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-3" style="margin-top:20px;">
                                <!-- <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                                    Cancel
                                </button> -->
                                <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                    Save
                                </button>
                                
                            </div>
                          </form>
                        </div>
                    </div>
               </div>
           </div> 
       </div>
    </div>
@endsection

@section('script')
   <script>
      // ================== Password Show Hide Js Start ==========
      function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on('click', function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    // Call the function
    initializePasswordToggle('.toggle-password');
  // ========================= Password Show Hide Js End ===========================
</script>

<script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
</script>

<script>
        $(document).ready(function() {
            $('.js-example-basic-singleet').select2({
                width: 'resolve'
            });
        });
</script>

<script>
         $(document).ready(function() {
            $('#country-dropdown').change(function() {
                var country_id = $(this).val();
                var stateDropdown = $('#state-dropdown');
                stateDropdown.empty().append('');

                $.ajax({
                    url: "{{ route('all_states') }}",
                    data: {country_id: country_id},
                    type: "GET",
                    success: function (response) {
                        const all_states = response.data;
                        
                        all_states.forEach(function(state) {
                        stateDropdown.append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                        console.log(all_states);

                     
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });  
            });
         });
    </script> 
    
    <script>
        $(document).ready(function() {
            $('#generate-password-btn').on('click', function() {
                var length = 12; // Desired password length
                var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
                var password = "";
                for (var i = 0; i < length; i++) {
                    password += charset.charAt(Math.floor(Math.random() * charset.length));
                }
                $('.generated-password').val(password);
            });
        });
    </script>
@endsection
  





