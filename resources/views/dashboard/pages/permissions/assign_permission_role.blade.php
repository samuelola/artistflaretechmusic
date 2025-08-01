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
  <h6 class="fw-semibold mb-0">Role-Permission</h6>
  
</div>

   
    <div class="card h-100 radius-12" style="
    height: 400px;
">
       <div class="card-body p-24">
           <div class="row">
               <div class="col-xxl-6 col-xl-8 col-lg-10">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignPermissionRoleModal">
                          Assign Permission to Role
                    </button>
               </div>
               <div class="col-md-12">
                    <div class="table-responsive" style="margin-top:20px">
                        <table class="table basic-border-table mb-0">
                            <thead>
                            <tr>
                                <th>Sn </th>
                                <th>Permission</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                $sn = 1;
                                @endphp
                                @foreach($permission_with_roles as $permission)
                                   <tr>
                                      <td>{{$sn++}}</td>
                                      <td>{{$permission->name}}</td>
                                      <td>
                                          @foreach($permission->roles as $role)
                                              <span class="badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{$role->name}}</span>
                                          @endforeach
                                      </td>
                                      <td>
                                          <a href="{{route('edit_permission_role',['id'=>$permission->id])}}" class="bg-info-focus text-info-main px-32 py-4 rounded-pill fw-medium text-sm">Edit</a>
                                          <button data-id ="{{$permission->id}}" data-name="{{$permission->name}}" class="bg-danger-focus text-danger-main px-32 py-4 rounded-pill fw-medium text-sm deletePermissionBtn" data-bs-toggle="modal" data-bs-target="#deletePermissionRoleModal">Delete</button>
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
      <div class="modal fade" id="assignPermissionRoleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    @csrf
                     <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">AssignPermission To Role</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                 <label class="form-label">Permission:</label>
                                <select id="permission_id" class="form-select" aria-label="Default select example" required>
                                    <option>--Select Permission --</option>
                                    @foreach($permissions as $permission)
                                    <option value="{{$permission->id}}">{{$permission->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="margin-top:20px;">
                                 <label class="form-label">Role:</label>
                                <select id="role_id" class="form-select" aria-label="Default select example" required>
                                    <option>--Select Role --</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                           
                           
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="AssignPermissionRoleForm" type="button" class="btn btn-primary">Assign</button>
                        </div>
                        
                </form>
            </div>
          </div>
    </div>
  <!--end modal-->

<!-- delete permissionrole Modal -->
<div class="modal fade" id="deletePermissionRoleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form>
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" name="permission_id" id="deletePermissionId"/>
                <p>Are you sure you want to delete the roles from the permission ?</p>
    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="deleteFormPermissionRoles" type="button" class="btn btn-primary">Delete</button>
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
  





