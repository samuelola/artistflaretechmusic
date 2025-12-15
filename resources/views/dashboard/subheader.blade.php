<div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <div class="dropdown">
          <!--<button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
            <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
          </button>-->

          <!--<div class="dropdown-menu to-top dropdown-menu-lg p-0">
            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-0">Message</h6>
              </div>
              <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
            </div>
            
           <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
            
            

           

            <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
              <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"> 
                <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                  <img src="assets/images/notification/profile-5.png" alt="">
                  <span class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                </span> 
                <div>
                  <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                  <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...</p>
                </div>
              </div>
              <div class="d-flex flex-column align-items-end"> 
                <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                <span class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-neutral-400 rounded-circle">0</span>
              </div>
            </a>

            

           </div>
            <div class="text-center py-12 px-16"> 
                <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Message</a>
            </div>
          </div>-->
        </div>

        <div class="dropdown">
          <button id="notification-bell" class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
            @if(auth()->user()->unreadNotifications->count() > 0)
            <span id="notification-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
            @else
                <span id="notification-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle d-none">
                    0
                </span>
            @endif
          </button>
          <div class="dropdown-menu to-top dropdown-menu-lg p-0">
            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
              </div>
              
            </div>
            
            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
    <div id="notification-list">
        @foreach(auth()->user()->notifications as $notification)
            @php
                $isUnread = is_null($notification->read_at);
            @endphp

            <a href="{{route('notifications.read',$notification->id) }}"
               class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between
               {{ $isUnread ? 'bg-primary-subtle fw-semibold' : '' }}">

                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">

                    <iconify-icon
                        icon="{{ $notification->data['icon'] ?? 'bitcoin-icons:verify-outline' }}"
                        class="icon text-xxl {{ $isUnread ? 'text-primary' : 'text-muted' }}">
                    </iconify-icon>

                    <div>
                        <p style="margin-left: -5px;" class="mb-0 text-sm text-w-200-px {{ $isUnread ? 'fw-semibold' : '' }}">
                            {{ is_array($notification->data['title'] ?? null)
                                ? implode(', ', $notification->data['title'])
                                : ($notification->data['title'] ?? 'Notification') }}
                        </p>

                        <p class="mb-0 text-sm text-w-200-px text-muted">
                            {{ \Illuminate\Support\Str::limit(
                                implode(', ', (array) ($notification->data['message'] ?? 'Notification')),
                                50,
                                '...'
                            ) }}
                        </p>

                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>

                {{-- Unread indicator --}}
                @if($isUnread)
                    <span class="badge bg-primary rounded-circle p-1"></span>
                @endif

            </a>
        @endforeach
    </div>
</div>


            <div class="text-center"> 
                <a href="{{route('notifications.index')}}" class="text-primary-600 fw-semibold text-md">See All Notification</a>
            </div>

          </div>
        </div>

        <div class="dropdown">
          <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
            @if(!is_null(auth()->user()))
              @if(empty(auth()->user()->profile_image))
                 <img src="{{asset('assets/images/user.png')}}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
              @else
                 <img src="{{asset('/profile_uploads/auth()->user()->profile_image')}}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
              @endif
              
            @else
            <img src="{{asset('assets/images/user.png')}}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
            @endif
            
          </button>
          <div class="dropdown-menu to-top dropdown-menu-sm">
            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{auth()->user()->first_name ?? ''}}</h6>
                <!-- <span class="text-secondary-light fw-medium text-sm">Admin</span> -->
              </div>
              <button type="button" class="hover-text-danger">
                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon> 
              </button>
            </div>
            <ul class="to-top-list">
              <li>
                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{route('profile')}}"> 
                <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon>  My Profile</a>
              </li>
              
              <li>

              <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="{{ route('dashboard.logout') }}" 
              onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
              <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
              </a>    
              <form id="frm-logout" action="{{ route('dashboard.logout') }}" method="POST" style="display: none;">
                 @csrf
              </form>
                
                
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    