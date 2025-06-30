<aside class="sidebar">
  <button type="button" class="sidebar-close-btn">
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
  </button>
  <div>
    <a href="index.html" class="sidebar-logo">
      <img src="{{asset('flare_main.png')}}" alt="site logo" class="light-logo" style="width:200px;">
      <img src="{{asset('flare_main.png')}}" alt="site logo" class="dark-logo" style="width:50px;">
      <img src="{{asset('flare_logo2.png')}}" alt="site logo" class="logo-icon" style="width:50px;">
      <!-- <img src="assets/images/logo.png" alt="site logo" class="light-logo">
      <img src="assets/images/logo-light.png" alt="site logo" class="dark-logo">
      <img src="assets/images/logo-icon.png" alt="site logo" class="logo-icon"> -->
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
      <li>
        <a href="{{route('subscription')}}">
          <!-- <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon> -->
          <iconify-icon icon="streamline:subscription-cashflow" width="14" height="14" class="menu-icon"></iconify-icon>
          <span>Subscription</span>
        </a>
      </li>
      <li class="dropdown">
        <a href="javascript:void(0)">
        <iconify-icon icon="mdi-light:music" width="24" height="24" ></iconify-icon>
          <span>Catalog\Releases</span> 
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="#"><iconify-icon icon="streamline-freehand:products-shopping-bags" width="24" height="24"></iconify-icon>Products</a>
          </li>
          <li>
            <a href="#"><iconify-icon icon="material-symbols-light:approval-delegation-outline" width="24" height="24"></iconify-icon>Pending Approval</a>
          </li>
          <li>
            <a href="#"><iconify-icon icon="material-symbols-light:track-changes" width="24" height="24"></iconify-icon>Tracks</a>
          </li>
          <li>
            <a href="#"><iconify-icon icon="iconoir:label" width="24" height="24"></iconify-icon>Label</a>
          </li>
        </ul>
      </li>
      
      
      <li class="dropdown">
        <a href="javascript:void(0)" class="menu-icon">
        <iconify-icon icon="hugeicons:analytics-up" width="24" height="24"></iconify-icon>
          <span>Analytics</span> 
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{route('analytics')}}"><iconify-icon icon="pajamas:live-stream" width="16" height="16"></iconify-icon>Streams/Downloads</a>
          </li>
          
        </ul>
      </li>
      
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