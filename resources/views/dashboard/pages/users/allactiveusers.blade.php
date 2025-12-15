@extends('dashboard.index')
@section('title')
  SuperAdmin
@endsection
@section('content')


@include('sweetalert::alert') 

@php 

$permissionedituserPermission = App\Models\PermissionRole::getPermission('delete-users',Auth::user()->role_id);
$permissiondeleteuserPermission = App\Models\PermissionRole::getPermission('edit-users',Auth::user()->role_id);

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
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">All Active Users</h6>
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
              <h6 class="text-lg mb-0">All Active users({{$activeusers}})</h6>
              <div>
               <!-- <a href="#" id="listView">
                   <iconify-icon icon="material-symbols-light:list" width="40" height="40"></iconify-icon>
                   
               </a> -->
               <!-- <a href="#" id="gridView">
                  
               </a>  -->
               <!-- <button id="listView">
                 <iconify-icon icon="material-symbols-light:list" width="40" height="40"></iconify-icon>
               </button>
               <button id="gridView" tooltip="grid view">
                  <iconify-icon icon="bitcoin-icons:grid-outline" width="36" height="36"></iconify-icon>
               </button> -->

              
               
            </div>
              
            </div>

            

            <!--add user here -->
            <div class="listView">
            <div class="table-responsive" style="margin-top:20px;" >
              <table id="myTable" class="table bordered-table mb-0 display">
                <thead>
                  <tr>
                          <th scope="col">Users</th>
                          <th scope="col">Registered On</th>
                          <th scope="col">Albums</th>
                          <th scope="col">Tracks</th>
                          <!-- <th scope="col">Language</th>
                          <th scope="col">Country</th>
                          <th scope="col">State</th> -->
                          <th scope="col" class="text-center">Status</th>
                          <th scope="col" class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                     @foreach($gget_all_users as $value)
                       <tr>
                       <td>
                            <div class="d-flex align-items-center">
                              <?php 
                                 if(is_null($value->profile_image)){
                                     ?><img src="assets/images/users/user1.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden"><?php  
                                 }else{
                                     ?><img src="/profile_uploads/{{$value->profile_image}}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden"><?php
                                 }
                              ?>
                              
                              <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-medium">{{$value->first_name ?? ""}}{{$value->last_name ?? ""}}</h6>
                                <span class="text-sm fw-medium">{{$value->email ?? ""}}</span>
                              </div>
                            </div>
                          </td>
                          <td>{{ \Carbon\Carbon::parse($value->join_date)->format('d/m/Y')}}</td>
                          <td>{{$value->albums ?? ""}}</td>
                          <td>{{$value->tracks ?? ""}}</td>
                          <td class="text-center"> 
                            <?php
                               
                                if($value->active == 'Yes'){
                                  ?><span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Active</span> <?php
                                }elseif($value->active == 'No'){
                                   ?><span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Not Active</span> <?php
                                }
                            ?>
                            
                          </td>
                          <td>
                           <?php 
                             $encrypted = encrypt($value->id);
                          
                           
                           ?>
                           <div style="float:left;margin-right: 8px;">
                           
                           <a href="{{route('viewdashboardusers',$encrypted)}}">
                              <iconify-icon icon="mage:edit" data-toggle="tooltip" title='Edit' width="24" height="24" style="color:#700084;"></iconify-icon>
                           </a>
                          
                           </div>
                          <div>
                            
                          <form method="POST" action="{{route('deleteUser',$encrypted)}}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <iconify-icon class="show_confirm" data-toggle="tooltip" title='Delete' icon="mdi-light:delete" width="24" height="24" style="color:red;"></iconify-icon>
                           </form>
                          </div>
                          </td>
                       </tr>
                     @endforeach
                </tbody>
              </table>
              </div>
                
                 
                
            </div>
            <!-- <div class="gridView" style="display: none;">
               
                <div class="row" style="margin-top:20px;" id="data-allgriduser">
                   
                </div>
                
               
                
            </div> -->
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



