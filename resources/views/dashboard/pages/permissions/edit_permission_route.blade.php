@extends('dashboard.index')
@section('title')
  | Edit Permission Route
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
  <h6 class="fw-semibold mb-0">Edit Role-Permission</h6>
  
</div>

   
    <div class="card p-0 radius-12" style="
    height: 400px;
">
       <div class="card-body p-24">
           
           <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                     <form method="post" action="{{route('update_permission_route')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Permission:</label>
                            <select name="permission_id" class="form-control js-example-basic-single" style="width: 100% !important">
                                 
                                @foreach($permissions as $permission)
                                    <option value="{{$permission->id}}" {{$permission->id == $getPermissionRolew->id ? 'selected' : ''}}>{{$permission->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Routes:</label>
                            <select id="exampler" name="routes[]" class="form-control js-example-basic-singlee" multiple="multiple" style="width: 100% !important">
                                 
                                @foreach($routeDetails as $routeDetail)
                                    <option value="{{$routeDetail['name']}}" @if($routeDetail['name'] == $permission_route->router) selected @endif >{{$routeDetail['name']}}</option>
                                @endforeach

                            </select>
                            
                        </div>
                        
                        
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
                <div class="col-md-3"></div>
           </div> 
       </div>
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


@endsection
  





