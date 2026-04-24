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

  @include('dashboard.ping')
  
  <div class="dashboard-main-body">
     <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">All Products</h6>

     </div>

        <div class="row">
                <div class="col-md-12">
                        @if(session('error'))
                            
                            <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        
                                        {!! session('error') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                </div>
        </div>

   
            <!--new row -->
                <div class="row gy-4">
                    @foreach($release_products as $item)
                        <div class="col-xxl-3 col-sm-4">
                            <div class="card h-100 radius-12">
                                @php
                                    $artworkPath = $item->artworks->first()->path ?? 'default.jpg';
                                    $storageUrl = env('R2_PUBLIC_URL');
                                @endphp
                                <img src="{{ $storageUrl . '/' . ltrim($artworkPath, '/') }}" class="card-img-top" alt="" style="height:300px;">
                                {{--<img src="{{ Storage::url($item->artworks->first()->path ?? 'default.jpg')}}" class="card-img-top" alt="" style="height:300px;">--}}
                                <div class="card-body p-16 text-left">
                                    <h5 class="card-title text-lg text-primary-light
                                    mb-6">{{$item->title}}</h5>
                                    <div class="d-flex justify-content-between ">
                                       <p class="card-text text-neutral-600">Artist: {{$item->user->first_name}} {{$item->user->last_name}}</p>
                                       <p class="card-text text-neutral-600">Tracks : ({{$item->tracks_count}})</p>
                                    </div>
                                    <p class="card-text text-neutral-600">Format: {{$item->release_type}}</p> 
                                    <p class="card-text text-neutral-600">Label Name: {{$item->label_name}}</p>    
                                    <p class="card-text text-neutral-600">Status:
                                       @if($item->distributed == 'yes')
                                         Released
                                       @elseif($item->distributed == 'no')  
                                         Not Released
                                       @endif 
                                    </p> 
                                    <div class="d-flex flex-wrap align-items-center justify-content-left gap-3">
                                        <a href="{{route('edit_music_form',$item->id)}}" class="btn btn-primary-600 text-white px-12 py-10 d-inline-flex align-items-center gap-2"> 
                                            Edit <iconify-icon icon="flowbite:edit-outline" width="16" height="16"></iconify-icon>
                                        </a>
                                        <a href="{{route('theTracks',$item->id)}}" class="btn btn-success-600 text-white px-12 py-10 d-inline-flex align-items-center gap-2"> 
                                            View Tracks <iconify-icon icon="flowbite:edit-outline" width="16" height="16"></iconify-icon>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    @endforeach    
                    
                </div>
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

@endsection



