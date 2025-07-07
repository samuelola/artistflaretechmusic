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
      <div class="row gy-4">
          <div class="col-lg-5">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                    <img src="{{asset('assets/images/user-grid/user-grid-bg1.png')}}" alt="" class="w-100 object-fit-cover">
                    <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                        <div class="text-center border border-top-0 border-start-0 border-end-0">
                            @if(empty($user_info->profile_image))
                              <img src="{{asset('assets/images/user-grid/user-grid-img13.png')}}" alt="" class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                            @else
                               <img src="/profile_uploads/{{$user_info->profile_image}}" alt="" class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                            @endif
                            
                            <h6 class="mb-0 mt-16">{{ucfirst($user_info->first_name ?? '')}} {{ucfirst($user_info->last_name ?? '')}}</h6>
                            <span class="text-secondary-light mb-16">{{$user_info->email ?? ''}}</span>
                        </div>
                        <div class="mt-24">
                            <h6 class="text-xl mb-16">Personal Info</h6>
                            <ul>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{ucfirst($user_info->first_name ?? '')}} {{ucfirst($user_info->last_name ?? '')}}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Role</span>
                                    <span class="w-70 text-secondary-light fw-medium">: 
                                    <?php 
                                        $role = \DB::table('roles')->where('id',$user_info->role_id)->first();
                                        if(is_null($role)){
                                            
                                            echo "Not Available";
                                        }else{
                                            echo $role->name;
                                        }
                                    ?>
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Email</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{$user_info->email ?? ''}}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Phone Number</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{$user_info->phone_number ?? 'None'}}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Country</span>
                                    <span class="w-70 text-secondary-light fw-medium">: 
                                    <?php 
                                        $country = \DB::table('countries')->where('iso2',$user_info->country)->first();
                                        echo $country->name;
                                    ?>
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> State</span>
                                    <span class="w-70 text-secondary-light fw-medium">: 
                                    <?php 
                                        $state = \DB::table('states')->where('id',$user_info->state)->first();
                                        if(is_null($state)){
                                            
                                            echo "Not Available";
                                        }else{
                                            echo $state->name;
                                        }
                                    ?>
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Status</span>
                                    <span class="w-70 text-secondary-light fw-medium">: 
                                    <?php
                               
                                            if($user_info->active == 'Yes'){
                                                ?><span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Active</span> <?php
                                            }elseif($user_info->active == 'No'){
                                                ?><span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Not Active</span> <?php
                                            }
                                        ?>
                           
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-1">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Albums</span>
                                    <span class="w-70 text-secondary-light fw-medium">:
                                    {{$user_info->albums}}
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-1">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Tracks</span>
                                    <span class="w-70 text-secondary-light fw-medium">:
                                    {{$user_info->tracks}}
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-1">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Joined Date</span>
                                    <span class="w-70 text-secondary-light fw-medium">:
                                    {{ \Carbon\Carbon::parse($user_info->join_date)->format('d/m/Y')}}
                                    </span>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
          </div>
          <div class="col-lg-7">
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
                                <form action="{{route('update_user_profile',['id'=>$user_info->id ])}}" method="post" enctype="multipart/form-data">  
                                   @csrf
                                   <div class="avatar-upload">
                                        <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                                <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                </label>
                                        </div>
                                        <div class="avatar-preview">
                                                <!-- <div id="imagePreview"> -->
                                                @if(empty($user_info->profile_image))
                                                <div id="imagePreview">
                                                </div>
                                                @else
                                                <div style="
                                                    background-image: url(/profile_uploads/{{$user_info->profile_image}});
                                                    background-size: cover;
                                                    background-repeat: no-repeat;
                                                    background-position: center;
                                                ">
                                                     
                                                </div>
                                                @endif    
                                                    
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">First Name <span class="text-danger-600">*</span></label>
                                                <input type="text" name="first_name" class="form-control radius-8" id="name" placeholder="Enter First Name" value="{{$user_info->first_name}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Last Name <span class="text-danger-600">*</span></label>
                                                <input type="text" name="last_name" class="form-control radius-8" id="name" placeholder="Enter Last Name" value="{{$user_info->last_name}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                                <input type="email" name="email" class="form-control radius-8" id="email" placeholder="Enter email address" value="{{$user_info->email}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                            <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Joined Date <span class="text-danger-600">*</span></label>
                                            <input type="text" readonly class="form-control radius-8" id="email" placeholder="Enter date" value="{{ \Carbon\Carbon::parse($user_info->join_date)->format('d/m/Y')}}">
                                            </div>
                                        </div>

                                        

                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone Number <span class="text-danger-600">*</span></label>
                                                <input type="phone" name="phone_number" class="form-control radius-8" id="email" value="{{$user_info->phone_number}}">
                                                @error('phone_number')
                                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Role<span class="text-danger-600">*</span></label>
                                                <select class="form-control" name="role_id">
                                                    <?php 
                                                       $roles = \DB::table('roles')->get();
                                                       foreach ($roles as $value) {
                                                         if($value->id == $user_info->role_id){

                                                          ?>
                                                             <option selected value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                                          <?php
                                                              
                                                         }else{
                                                            ?>
                                                            <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                                         <?php
                                                         }
                                                          
                                                       }

                                                    ?>
                                                    
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Actions <span class="text-danger-600">*</span></label>
                                                <select class="form-control" name="user_status">
                                                  <option selected value="1">Activate</option> 
                                                  <option value="0">Deactivate</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        
                                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"> 
                                           Update
                                        </button>
                                    </div>
                                </form>
                          
                            </div>

                            <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab" tabindex="0">
                                <!-- <h6 class="text-md text-primary-light mb-16">Change Password</h6> -->
                                <form method="post" action="{{route('change.user.password',['id'=>$user_info->id ])}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-20">
                                            <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Current Password <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password" name="current_password" class="form-control radius-8" id="your-password" placeholder="Enter New Password*" required>
                                                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                            </div>
                                            @error('current_password')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
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
           <!--end for 8g-->
           </div>
  </div>

@endsection

