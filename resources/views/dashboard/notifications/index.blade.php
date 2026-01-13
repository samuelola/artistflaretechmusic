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
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">All Notifications</h6>

</div>

        <div class="row">
                <div class="col-md-12">
                                    @forelse($notifications as $notification)
                                        <div class="border rounded p-3 mb-2 d-flex align-items-start">
                                            
                                            <!-- LEFT: Message (takes remaining space) -->
                                            <div class="flex-grow-1 me-3">
                                                <strong>
                                                    {{ is_array($notification->data['title'] ?? null)
                                                        ? implode(', ', $notification->data['title'])
                                                        : ($notification->data['title'] ?? 'Notification') }}
                                                </strong>

                                                <p class="mb-0 text-wrap">
                                                    {!! is_array($notification->data['message'] ?? null)
                                                        ? implode(', ', $notification->data['message'])
                                                        : ($notification->data['message'] ?? 'Notification') !!}
                                                </p>
                                            </div>

                                            <!-- RIGHT: Time (never wraps or shifts) -->
                                            <small class="text-muted text-nowrap">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>

                                        </div>
                                    @empty
                                        <p>No notifications yet.</p>
                                    @endforelse
                                </div>

        </div><!--new row -->
               
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

@endsection



