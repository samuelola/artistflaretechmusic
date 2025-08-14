@foreach($allsubs as $allsub)
                    
    <div class="col-xxl-4 col-sm-4 pricing-plan-wrapper">
        <div class="pricing-plan position-relative radius-24 overflow-hidden border" style="background-color:{{$allsub->display_color}}">
            <div class="d-flex align-items-center gap-16">
                <span class="w-72-px h-72-px d-flex justify-content-center align-items-center radius-16 bg-base">
                    <img src="assets/images/pricing/price-icon1.png" alt="">
                </span>
                <div class="">
                    <!-- <span class="fw-medium text-md text-secondary-light">With Revenue Sharing</span> -->
                    <h6 class="mb-0" style="color:#f6f6f7;">{{$allsub->subscription_name ?? ''}}</h6>
                </div>
               
            </div>
            <p class="mt-16 mb-0 text-secondary-light mb-28">{{$allsub->plan_info_text ?? ''}}</p>
            <!-- <h3 class="mb-24" style="color:#f6f6f7;">$99 <span class="fw-medium text-md text-secondary-light">/monthly</span> </h3> -->
            <h3 class="mb-24" style="color:#f6f6f7;font-size: 22px !important;">
                <?php
                   $curr = DB::table('currency')->where('code',$allsub->currency)->first();
                   $basecurrSymbol = DB::table('currency')->where('code',$currencyExchangeRate->rate_symbol)->first();
                   $amount = $allsub->subscription_amount/$currencyExchangeRate->rate;
                 ?>
                 <!-- currencyExchangeRate -->
                {{$curr->symbol}}{{number_format($allsub->subscription_amount ?? '', 2, '.', ',')}} / {{$basecurrSymbol->symbol ?? ''}}{{number_format($amount ?? '', 2, '.', ',')}}
            </h3>
            <span class="mb-20 fw-medium" style="color:#f6f6f7;">What’s included</span>
            <ul>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->artist_no ?? ''}} Artist</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->no_of_tracks ?? ''}} Tracks</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->no_of_products ?? ''}} Products</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->subscription_limit_per_year ?? ''}} &nbsp; Limit/Year</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">Duration &nbsp; {{$allsub->subscription_duration ?? ''}}</span>
                </li>
            </ul>
            
            <div style="display:flex;
  justify-content:center;
  align-items:center;">
              <form action="{{route('checkout_subscription')}}" method="post">
                 @csrf
                   <input type="hidden" name="sub_id" value="{{$allsub->id}}"/>
                   <button type="submit" id="start_subscription" class="btn btn-success-600 radius-8 px-16 py-9">Get Started</button>
              </form>
             
            </div>
            
        </div>
    </div>
@endforeach  