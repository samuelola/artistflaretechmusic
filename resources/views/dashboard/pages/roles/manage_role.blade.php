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
  <h6 class="fw-semibold mb-0">Create Role</h6>
  
</div>

   
    <div class="card h-100 radius-12" style="
    height: 400px;
">
       <div class="card-body p-24">
           <div class="row">
               <div class="col-xxl-6 col-xl-8 col-lg-10">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal">
                          Create Role
                    </button>
               </div>
               <div class="col-md-12">
                    <div class="table-responsive" style="margin-top:20px">
                        <table class="table basic-border-table mb-0">
                            <thead>
                            <tr>
                                <th>Sn </th>
                                <th>Name</th>
                                 <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                             @php
                                $sn = 1;
                             @endphp
                             @foreach($roles as $role)    
                            <tr>
                                <td>{{$sn++}}</td>
                                <td>
                                  <span class="badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{$role->name}}</span>
                                </td>
                                <td>
                                
                                        <button data-id ="{{$role->id}}" data-name="{{$role->name}}" class="bg-info-focus text-info-main px-32 py-4 rounded-pill fw-medium text-sm editRoleBtn" data-bs-toggle="modal" data-bs-target="#updateRoleModal">Edit</button>
                                        <button data-id ="{{$role->id}}" data-name="{{$role->name}}" class="bg-danger-focus text-danger-main px-32 py-4 rounded-pill fw-medium text-sm deleteRoleBtn" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">Delete</button>
                                   
                                </td>
                            </tr>
                            @endforeach
                          
                            
                            </tbody>
                        </table>
                        </div>
               </div>
           </div> 
       </div>
    </div>

    

  <!--create role modal-->
      <div class="modal fade" id="roleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    @csrf
                     <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Role</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Role</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Role" value="{{ old('name') }}">
                            
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="createRoleForm" type="button" class="btn btn-primary">Create</button>
                        </div>
                        
                </form>
            </div>
          </div>
    </div>
  <!--end modal-->

  <!--start modal-->
 
<!-- delete role Modal -->
<div class="modal fade" id="deleteRoleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form>
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="role_id" id="deleteRoleId"/>
                <p>Are you sure you want to delete the <span class="delete-role text-white bg-lilac-600 border border-lilac-600 radius-4 px-8 py-4 text-sm line-height-1 fw-medium"></span> Role?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="deleteFormRole" type="button" class="btn btn-primary">Delete</button>
            </div>
        </form>
    </div>
  </div>
</div>
  <!--end modal-->


<!--update role modal-->
      <div class="modal fade" id="updateRoleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    @csrf
                     <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Role</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Role</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Role" id="updateRoleName" value="">
                            <input type="hidden" name="role_id" id="updateRoleId"/>
                            
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="updateRoleBtn" type="button" class="btn btn-primary">Update</button>
                        </div>
                        
                </form>
            </div>
          </div>
    </div>
  <!--end modal-->  

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
        $("#createRoleForm").click(function(e){
            e.preventDefault();
            $("#createRoleForm").prop('disabled',true)
            var role = $("#name").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('create_role')}}",
                type: "POST",
                data : {name:role},
                success:function(response){
                    $("#createRoleForm").prop('disabled',false)
                    if(response.success){
                       alert(response.msg)
                       $('#roleModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg)
                    }
                }
            })
        })

        $('.deleteRoleBtn').click(function(){
          var roleId = $(this).data("id");
          var roleName = $(this).data("name");

          $('.delete-role').text(roleName);
          $('#deleteRoleId').val(roleId);

        });

         $("#deleteFormRole").click(function(e){
            e.preventDefault();
            var deleterole = $("#deleteRoleId").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('delete_role')}}",
                type: "POST",
                data : {role_id:deleterole},
                success:function(response){
                    if(response.success){
                       alert(response.msg)
                       $('#deleteRoleModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg)
                    }
                }
            })
        })


        $('.editRoleBtn').click(function(){
          var roleId = $(this).data("id");
          var roleName = $(this).data("name");
          $('#updateRoleId').val(roleId);
          $('#updateRoleName').val(roleName);
        });



        $('#updateRoleBtn').click(function(e){
            e.preventDefault();
            var updateroleid = $("#updateRoleId").val();
            var updateRoleName = $("#updateRoleName").val();
            
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('update_role')}}",
                type: "POST",
                data : {role_id:updateroleid,roleName:updateRoleName},
                success:function(response){
                    if(response.success){
                       alert(response.msg)
                       $('#updateRoleModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg)
                    }
                }
            })
        });

        
        
    }); 

</script>


    
    
@endsection
  





