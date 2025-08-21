 @php 
   $user_role_artist = App\Enum\UserStatus::Artist;
   $user_role_guest = App\Enum\UserStatus::Guest;
   $dateAfter = \DB::table('sub_count')->where('user_id',auth()->user()->id)->first();
   $d_date = $dateAfter->expires_at ?? '';
   $get_active_sub = \DB::table('sub_count')->where('user_id',auth()->user()->id)->first();
  @endphp

   @if(Auth::user()->role_id == $user_role_artist)
       @if(!is_null($get_active_sub))
        <div class="d-flex align-items-center gap-2 alert alert-primary bg-primary-50 text-primary-600 border-primary-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center gap-2">
                <iconify-icon icon="mingcute:emoji-line" class="icon text-xl"></iconify-icon>
                Your Subscription expires on {{ Carbon\Carbon::parse($d_date)->format('d,M Y') }}
            </div>
        
        </div>
       @else
           <div class="alert alert-info bg-info-100 text-info-600 border-info-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="ci:link" class="icon text-xl"></iconify-icon>
                    You have no active subscription,subscribe <a style="color:#700084;" href="{{route('choosesubscription')}}">here</a>
                </div>
            </div>
       @endif  
      
   @elseif(Auth::user()->role_id == $user_role_guest)

         @if(is_null($get_active_sub))
            <div class="alert alert-info bg-info-100 text-info-600 border-info-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="ci:link" class="icon text-xl"></iconify-icon>
                    You have no active subscription,subscribe <a style="color:#700084;" href="{{route('choosesubscription')}}">here</a>
                </div>
            </div>
         @else

            @if (now()->greaterThan($d_date))
                <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="mingcute:delete-2-line" class="icon text-xl"></iconify-icon>
                        Your Subscription has expired 
                    </div>
                    
                </div>
                
            @endif

         @endif
        
   @endif

   