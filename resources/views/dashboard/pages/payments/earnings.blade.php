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
      <h6 class="fw-semibold mb-0">Earnings</h6>
  
    </div>

   
    <div class="row">
        <!--begining of firstchart-->
        <div class="col-xxl-4">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                        <h6 class="mb-2 fw-bold text-lg">Total Sales </h6>
                        <div class="">
                        <!-- <select class="form-select form-select-sm w-auto bg-base border text-secondary-light radius-8">
                            <option>Yearly</option>
                            <option>Monthly</option>
                            <option>Weekly</option>
                            <option>Today</option>
                        </select> -->
                        </div>
                    </div>

                    <ul class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-28">
                        <li class="d-flex align-items-center gap-2">
                            <span class="w-16-px h-16-px radius-2 bg-primary-600"></span>
                            <span class="text-secondary-light text-lg fw-normal">Total Sales: 
                                <span class="text-primary-light fw-bold text-lg">$50,000</span>
                            </span>
                        </li>
                    </ul>

                    <div id="transactionLineChartt"></div>
                    
                    
                </div>
            </div>
        </div>
        <!--end of firstchart-->

        <!--begining of firstchart-->

        <div class="col-xxl-3" style="margin-top:20px;">
             <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Streaming Services</h6>
                        </div>
                    </div>
                    <div class="card-body p-24 d-flex align-items-center gap-16">
                        <div id="radialMultipleBar"></div>
                        <ul class="d-flex flex-column gap-12">
                            <li>
                                <span class="text-lg">Spotify: <span class="text-primary-600 fw-semibold">80%</span> </span>
                            </li>
                            <!-- <li>
                                <span class="text-lg">Psychiatry:  <span class="text-warning-600 fw-semibold">40%</span> </span>
                            </li>
                            <li>
                                <span class="text-lg">Pediatrics: <span class="text-success-600 fw-semibold">10%</span> </span>
                            </li> -->
                        </ul>
                    </div>
              </div>
        </div>

        <!--end of firstchart-->
        <!--begining of firstchart-->
        <div class="col-xxl-3" style="margin-top:20px;">
            <div class="card radius-8 border-0">
    
              <div class="card-body">
                  <h6 class="mb-2 fw-bold text-lg">Most Location</h6>
              </div>
    
              <div id="world-map" style="height: 300px;"></div>
              
              <div class="card-body p-24 max-h-266-px scroll-sm overflow-y-auto">
                <div>
    
                  <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-2">
                    <div class="d-flex align-items-center w-100">
                      <img src="assets/images/flags/flag1.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                      <div class="flex-grow-1">
                        <h6 class="text-sm mb-0">USA</h6>
                        <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                      </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 w-100">
                      <div class="w-100 max-w-66 ms-auto">
                        <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar bg-primary-600 rounded-pill" style="width: 80%;"></div>
                        </div>
                      </div>
                      <span class="text-secondary-light font-xs fw-semibold">80%</span>
                    </div>
                  </div>
    
                  <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-2">
                    <div class="d-flex align-items-center w-100">
                      <img src="assets/images/flags/flag2.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                      <div class="flex-grow-1">
                        <h6 class="text-sm mb-0">Japan</h6>
                        <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                      </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 w-100">
                      <div class="w-100 max-w-66 ms-auto">
                        <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar bg-orange rounded-pill" style="width: 60%;"></div>
                        </div>
                      </div>
                      <span class="text-secondary-light font-xs fw-semibold">60%</span>
                    </div>
                  </div>
    
                  <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-2">
                    <div class="d-flex align-items-center w-100">
                      <img src="assets/images/flags/flag3.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                      <div class="flex-grow-1">
                        <h6 class="text-sm mb-0">France</h6>
                        <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                      </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 w-100">
                      <div class="w-100 max-w-66 ms-auto">
                        <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar bg-yellow rounded-pill" style="width: 49%;"></div>
                        </div>
                      </div>
                      <span class="text-secondary-light font-xs fw-semibold">49%</span>
                    </div>
                  </div>
    
                  <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center w-100">
                      <img src="assets/images/flags/flag4.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                      <div class="flex-grow-1">
                        <h6 class="text-sm mb-0">Germany</h6>
                        <span class="text-xs text-secondary-light fw-medium">1,240 Users</span>
                      </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 w-100">
                      <div class="w-100 max-w-66 ms-auto">
                        <div class="progress progress-sm rounded-pill" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar bg-success-main rounded-pill" style="width: 100%;"></div>
                        </div>
                      </div>
                      <span class="text-secondary-light font-xs fw-semibold">100%</span>
                    </div>
                  </div>
    
                </div>
                
              </div>
            </div>
        </div>
        <!--end of firstchart-->
        <!--begining of firstchart-->
        
        <!--end of firstchart-->

    </div>

    



@endsection

@section('script')
   

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
        $("#AssignPermissionRoleForm").click(function(e){
            e.preventDefault();
            // $("#AssignPermissionRoleForm").prop('disabled',true)
            var role_id = $("#role_id").val();
            var permission_id = $("#permission_id").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('create_permission_role')}}",
                type: "POST",
                data : {role_id:role_id,permission_id:permission_id},
                success:function(response){
                    $("#AssignPermissionRoleForm").prop('disabled',false)
                    if(response.success){
                       alert(response.msg)
                       $('#assignPermissionRoleModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg)
                    }
                }
            })
        })


        $('.deletePermissionBtn').click(function(){
          var permissionId = $(this).data("id");
          var permissionName = $(this).data("name");

          //$('.delete-permission').text(permissionName);
          $('#deletePermissionId').val(permissionId);

        });

        $("#deleteFormPermissionRoles").click(function(e){
            e.preventDefault();
            //$("#deleteFormPermissionRoles").prop('disabled',true)
            var deletepermission = $("#deletePermissionId").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('delete_permission_role')}}",
                type: "POST",
                data : {permission_id:deletepermission},
                success:function(response){
                    //$("#deleteFormPermissionRoles").prop('disabled',false)
                    if(response.success){
                       alert(response.msg)
                       $('#deletePermissionModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg);
                    }
                }
            })
        })
        
    }); 

</script>

@endsection
  





