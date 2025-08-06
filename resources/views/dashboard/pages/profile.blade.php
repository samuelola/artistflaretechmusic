@extends('dashboard.index')
@section('title')
    Profile
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
  

          <div class="col-lg-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                                Edit Profile 
                              </button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                Change Password 
                              </button>
                            </li>
                            
                        </ul>

                        <div class="tab-content" id="pills-tabContent">   
                            <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab" tabindex="0">
                                <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                                <!-- Upload Image Start -->
                                <form action="{{route('update_profile')}}" method="post" enctype="multipart/form-data"> 
                                    <div class="mt-16">
                                    <div class="avatar-upload">
                                             <div class="avatar-edit bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer" style="position: relative;
    left: 100px;
    top: 150px;">
                                                <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                                <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                </label>
                                            </div>
                                            <div class="avatar-preview">
                                                <!-- <div id="imagePreview"> -->
                                                <div style="
                                                        background-image: url(/profile_uploads/{{auth()->user()->profile_image}});
                                                        background-size: cover;
                                                        background-repeat: no-repeat;
                                                        background-position: center;
                                                    ">
                                                     
                                                </div>    
                                            </div>

                                            <div class="row" style="margin-top: 30px;">
                                               <div class="col-sm-6">
                                                <div class="mb-20">
                                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">First Name <span class="text-danger-600">*</span></label>
                                                        <input type="text" name="first_name" class="form-control radius-8" id="name" placeholder="Enter First Name" value="{{auth()->user()->first_name}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Last Name <span class="text-danger-600">*</span></label>
                                                        <input type="text" name="last_name" class="form-control radius-8" id="name" placeholder="Enter Last Name" value="{{auth()->user()->last_name}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                                        <input type="email" name="email" class="form-control radius-8" id="email" placeholder="Enter email address" value="{{auth()->user()->email}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Joined Date <span class="text-danger-600">*</span></label>
                                                    <input type="text" readonly class="form-control radius-8" id="email" placeholder="Enter date" value="{{ \Carbon\Carbon::parse(auth()->user()->join_date)->format('d/m/Y')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center gap-3">
                                        
                                                <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                                Update
                                                </button>
                                            </div>
                                    </div>
                                    </div>
                                    
                                </form>
                            </div>

                            <div class="tab-pane fade show" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab" tabindex="0">
                                <!-- <h6 class="text-md text-primary-light mb-16">Change Password</h6> -->
                                <!-- Upload Image Start -->
                                 <form method="post" action="{{route('change.password',['id'=>auth()->user()->id])}}">
                                    @csrf
                                        <!-- <div class="mb-20">
                                            <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Current Password <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" name="current_password" class="form-control radius-8" id="your-password" placeholder="Enter New Password*" required>
                                                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                            </div>
                                            @error('current_password')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div> -->
                                        <div class="mb-20">
                                            <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">New Password <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" name="new_password" class="form-control radius-8" id="your-passwordd" placeholder="Enter New Password*" required>
                                                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-passwordd"></span>
                                            </div>
                                            @error('new_password')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="mb-20">
                                            <label for="confirm-password" name="confirm_password" class="form-label fw-semibold text-primary-light text-sm mb-8">Confirmed Password <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control radius-8" id="confirm-passwordd" placeholder="Confirm Password*" required>
                                                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#confirm-passwordd"></span>
                                            </div>
                                            @error('confirm_password')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                                
                                                <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                                Update
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

@endsection