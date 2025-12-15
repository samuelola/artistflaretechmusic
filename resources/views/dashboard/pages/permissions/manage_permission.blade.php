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
  <h6 class="fw-semibold mb-0">Create Permission</h6>
  
</div>

   
    <div class="card h-100 radius-12" style="
    height: 400px;
">
       <div class="card-body p-24">
           <div class="row">
               <div class="col-xxl-6 col-xl-8 col-lg-10">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#permissionModal">
                          Create Permission
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
                             @foreach($permissions as $permission)    
                            <tr>
                                <td>{{$sn++}}</td>
                                <td>
                                  <span class="badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{$permission->name}}</span>
                                </td>
                                <td>
                                
                                    <button data-id ="{{$permission->id}}" data-name="{{$permission->name}}" class="bg-info-focus text-info-main px-32 py-4 rounded-pill fw-medium text-sm editPermissionBtn" data-bs-toggle="modal" data-bs-target="#editPermissionModal">Edit</button>
                                    <button data-id ="{{$permission->id}}" data-name="{{$permission->name}}" class="bg-danger-focus text-danger-main px-32 py-4 rounded-pill fw-medium text-sm deletePermissionBtn" data-bs-toggle="modal" data-bs-target="#deletePermissionModal">Delete</button>
                                   
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
      <div class="modal fade" id="permissionModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    @csrf
                     <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Permission</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Permission</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Permission" value="{{ old('name') }}">
                            
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="createPermissionForm" type="button" class="btn btn-primary">Create</button>
                        </div>
                        
                </form>
            </div>
          </div>
    </div>
  <!--end modal-->
  
     <!-- delete role Modal -->
<div class="modal fade" id="deletePermissionModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form>
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="permission_id" id="deletePermissionId"/>
                <p>Are you sure you want to delete the <span class="delete-permission text-white bg-lilac-600 border border-lilac-600 radius-4 px-8 py-4 text-sm line-height-1 fw-medium"></span> Permission?</p>
                <p>If you delete this permission, then this permission is deleted from All users</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="deleteFormPermission" type="button" class="btn btn-primary">Delete</button>
            </div>
        </form>
    </div>
  </div>
</div>
  <!--end modal-->


<!--update role modal-->
      <div class="modal fade" id="editPermissionModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    @csrf
                     <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Permission</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Permission</label>
                            <input type="text" name="name" class="form-control"  id="updatePermissionName" value="">
                            <input type="hidden" name="permission_id" id="updatePermissionId"/>
                            
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="updatePermissionBtn" type="button" class="btn btn-primary">Update</button>
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
        $("#createPermissionForm").click(function(e){
            e.preventDefault();
            $("#createPermissionForm").prop('disabled',true)
            var permission = $("#name").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('create_permission')}}",
                type: "POST",
                data : {name:permission},
                success:function(response){
                    $("#createPermissionForm").prop('disabled',false)
                    if(response.success){
                       alert(response.msg)
                       $('#permissionModal').modal('hide');
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

          $('.delete-permission').text(permissionName);
          $('#deletePermissionId').val(permissionId);

        });

         $("#deleteFormPermission").click(function(e){
            e.preventDefault();
            var deletepermission = $("#deletePermissionId").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('delete_permission')}}",
                type: "POST",
                data : {permission_id:deletepermission},
                success:function(response){
                    if(response.success){
                       alert(response.msg)
                       $('#deletePermissionModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg)
                    }
                }
            })
        })


        // edit permission

        $('.editPermissionBtn').click(function(){
          var roleId = $(this).data("id");
          var roleName = $(this).data("name");
          $('#updatePermissionId').val(roleId);
          $('#updatePermissionName').val(roleName);
        });

        $('#updatePermissionBtn').click(function(e){
            e.preventDefault();
            var updatepermissionid = $("#updatePermissionId").val();
            var updatePermissionName = $("#updatePermissionName").val();
            
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('update_permission')}}",
                type: "POST",
                data : {permission_id:updatepermissionid,permissionName:updatePermissionName},
                success:function(response){
                    if(response.success){
                       alert(response.msg)
                       $('#editPermissionModal').modal('hide');
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
  





