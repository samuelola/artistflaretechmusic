
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<aside class="sidebar">
  @php
  $permissionUser = App\Models\PermissionRole::getPermission('users',Auth::user()->role_id);
  $permissionSubscription = App\Models\PermissionRole::getPermission('Subscription',Auth::user()->role_id);
  $permissionaddsub = App\Models\PermissionRole::getPermission('add-subscription',Auth::user()->role_id);
  $permissionaddallsub = App\Models\PermissionRole::getPermission('all-subscriptions',Auth::user()->role_id);
  $permissionroles = App\Models\PermissionRole::getPermission('Roles',Auth::user()->role_id);
  $permissionPermissions = App\Models\PermissionRole::getPermission('Permissions',Auth::user()->role_id);
  $permissionedituserPermission = App\Models\PermissionRole::getPermission('delete-users',Auth::user()->role_id);
  $permissiondeleteuserPermission = App\Models\PermissionRole::getPermission('edit-users',Auth::user()->role_id);
  $permissionchooseSubscriptionPermission = App\Models\PermissionRole::getPermission('choose_subscription',Auth::user()->role_id);
  $user_role_artist = App\Enum\UserStatus::Artist;
  $user_role_admin = App\Enum\UserStatus::Admin;
  $user_role_superadmin = App\Enum\UserStatus::SuperAdmin;
  $user_role_guest = App\Enum\UserStatus::Guest;
  $permissionPayout = App\Models\PermissionRole::getPermission('Payout',Auth::user()->role_id);
  $permissionPayout = App\Models\PermissionRole::getPermission('Payout',Auth::user()->role_id);
  $permissionTransaction = App\Models\PermissionRole::getPermission('Transaction',Auth::user()->role_id);
  $permissionRelease = App\Models\PermissionRole::getPermission('New Release',Auth::user()->role_id);
  $permissionCatalog = App\Models\PermissionRole::getPermission('Catalog',Auth::user()->role_id);
  $subrelease = \DB::table('music_releases')->where(['user_id'=>auth()->user()->id,'status'=>'submitted'])->count();                                  
  $subcountt = App\Models\SubCount::with(['subscription'])->where(['user_id'=>auth()->user()->id,'status'=>'active'])
                    ->first();                   
                    
  @endphp
  <button type="button" class="sidebar-close-btn">
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
  </button>
  <div>
    <a href="{{route('dashboard')}}" class="sidebar-logo">
      <img src="{{asset('flare_main.png')}}" alt="site logo" class="light-logo" style="width:200px;">
      <img src="{{asset('flare_main.png')}}" alt="site logo" class="dark-logo" style="width:50px;">
      <img src="{{asset('flare_logo2.png')}}" alt="site logo" class="logo-icon" style="width:50px;">
      
    </a>
  </div>
  <div class="sidebar-menu-area">
    <ul class="sidebar-menu" id="sidebar-menu">
      <li class="sidebar-menu-group-title">Application</li>
      <li>
        <a href="{{route('dashboard')}}">
          <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
          <span>Dashboard</span>
        </a>
      </li>

      @if(!empty($permissionUser) && Auth::user()->role_id != $user_role_artist)
      <li class="dropdown">
        <a href="javascript:void(0)" class="menu-icon">
        <iconify-icon icon="raphael:users" width="16" height="16" style="margin-inline-end: 0.4rem;"></iconify-icon>
          <span>Users</span> 
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{route('allUser')}}">
              <iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>
              All Users</a>
          </li>
          <li>
            <a href="{{route('allActiveUser')}}">
              <iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>
              Active Users</a>
          </li>
          <li>
            <a href="{{route('allInactiveUser')}}">
              <iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>
              Inactive Users</a>
          </li>
          
        </ul>
      </li>
      @endif
      

      @if(!empty($permissionSubscription))
      <li class="dropdown">
        <a href="javascript:void(0)">
        <iconify-icon icon="streamline:subscription-cashflow" width="16" height="16"  class="menu-icon"></iconify-icon>
          <span>Subscription</span> 
        </a>
        <ul class="sidebar-submenu">
          @if(!empty($permissionaddsub))
          <li>
            <a href="{{route('subscription')}}"><iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>Add Subscription</a>
          </li>
          @endif  

          @if(!empty($permissionaddallsub))
          <li>
          
            <a href="{{route('allsubscription')}}"><iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>All Subscriptions</a>
          </li>
          @endif 
          
          @if(!empty($permissionchooseSubscriptionPermission))
          <li>
            <a href="{{route('choosesubscription')}}"><iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>Choose Subscriptions</a>
          </li>
          @endif

        </ul>
      </li>
      @endif

      @if(Auth::user()->role_id == $user_role_guest)
          <li class="dropdown">
            <a href="javascript:void(0)">
            <iconify-icon icon="streamline:subscription-cashflow" width="16" height="16"  class="menu-icon"></iconify-icon>
              <span>Subscription</span> 
            </a>
            <ul class="sidebar-submenu">
             <li>
              <a href="{{route('choosesubscription')}}"><iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>Choose Subscriptions</a>
            </li>
            </ul>
         </li>  
      @endif

      
      @if(!empty($permissionRelease))

            @if(!is_null($subrelease))
              @if($subrelease == 1 && $subcountt?->subscription->subscription_name == 'Easy-Buy')

              <li>
                <a href="{{route('releases.create')}}" style="cursor: pointer;" id="subcount">
                <iconify-icon icon="pixel:analytics" width="16" height="16" class="menu-icon"></iconify-icon>
                  <span>New Release</span> 
                </a>
              
              </li>

            @else 
              <li>
                  <a href="{{route('releases.create')}}">
                  <iconify-icon icon="pixel:analytics" width="16" height="16" class="menu-icon"></iconify-icon>
                    <span>New Release</span> 
                  </a>
                
                </li>
            @endif
        
        @else

           <li>
              <a href="{{route('releases.create')}}">
              <iconify-icon icon="pixel:analytics" width="16" height="16" class="menu-icon"></iconify-icon>
                <span>New Release</span> 
              </a>
            
           </li>
        
      @endif
         
      @endif

          {{--   <li>
          {{--      <a href="{{route('music_release')}}">
          {{--      <iconify-icon icon="pixel:analytics" width="16" height="16" class="menu-icon"></iconify-icon>
          {{--        <span>New Modify</span> 
          {{--      </a>
            
            </li> --}}

      
      @if(!empty($permissionCatalog))
       <li class="dropdown">
            <a href="javascript:void(0)">
           
            <iconify-icon icon="carbon:change-catalog" width="16" height="16" class="menu-icon"></iconify-icon>
              <span>Catalog/Releases</span> 
            </a>
            <ul class="sidebar-submenu">
              <li>
                <a href="{{route('music_product')}}"><iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>Products</a>
              </li>
              
              {{--<li>
                <a href="{{route('music_labels')}}"><iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>Labels</a>
              </li>
              <li>
                <a href="{{route('music_artist')}}"><iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>Artists</a>
              </li>--}}
            </ul>
       </li>  
      @endif
      

     
     @if(Auth::user()->role_id == $user_role_artist)
      <li>
        <a href="{{route('admin_analytics')}}">
        <iconify-icon icon="pixel:analytics" width="16" height="16" class="menu-icon"></iconify-icon>
           <span>Analytics</span> 
        </a>
       
      </li>

      <li>
        <a href="{{route('flaretech-monetize-songs')}}">
        <iconify-icon icon="pixel:analytics" width="16" height="16" class="menu-icon"></iconify-icon>
           <span>Monetize Songs</span> 
        </a>
       
      </li>
      @if(!empty($permissionPayout))
      <li class="dropdown">
        <a href="javascript:void(0)" class="menu-icon">
        <iconify-icon icon="hugeicons:payment-02" width="16" height="16" style="margin-inline-end: 0.4rem;"></iconify-icon>
          <span>Payout</span> 
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{route('payment')}}">
              <iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>
              Payments</a>
          </li>
          <li>
            <a href="{{route('earnings')}}">
              <iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>
              Earnings</a>
          </li>

          
          <!-- <li>
            <a href="{{route('split_sheet')}}">
              <iconify-icon icon="bi:dash" width="16" height="16"></iconify-icon>
              Split Sheet</a>
          </li> -->
        </ul>
      </li>

        @endif

    @endif
      

      
    @if(!empty($permissionroles))  
      <li>
        <a href="{{route('manage_role')}}">
        
        <iconify-icon icon="oui:app-users-roles" width="16" height="16" class="menu-icon"></iconify-icon>
           <span>Roles</span> 
        </a>
       
      </li>
    @endif

    @if(!empty($permissionTransaction))
    <li>
        <a href="{{route('transactions')}}">
        <iconify-icon icon="tdesign:undertake-transaction" width="16" height="16" class="menu-icon"></iconify-icon>
           <span>Transactions</span> 
        </a>
       
     </li>
      
    @endif
      
      <li>
        <a href="{{ route('dashboard.logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
         <iconify-icon icon="lucide:power" class="menu-icon"></iconify-icon>
           <span>Logout</span> 
        </a>
        <form id="frm-logout" action="{{ route('dashboard.logout') }}" method="POST" style="display: none;">
                 @csrf
        </form>
      </li>
      
    </ul>
  </div>
</aside>

<script>
   $(document).ready(function() {
    $('#subcount').on('click', function(e) {
        e.preventDefault(); // Prevent navigation
        alert('You can only upload 1 Single for this Plan'); // Show alert
        let redirectUrl = "{{ route('choosesubscription') }}"; 
        window.location.href = redirectUrl;
    });
});
</script>

