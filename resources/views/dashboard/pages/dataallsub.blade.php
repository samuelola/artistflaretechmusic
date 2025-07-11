@foreach($allsubs as $allsub)
                    
    <div class="col-xxl-4 col-sm-4 pricing-plan-wrapper">
        <div class="pricing-plan position-relative radius-24 overflow-hidden border" style="background-color:{{$allsub->display_color}}">
            <div class="d-flex align-items-center gap-16">
                <span class="w-72-px h-72-px d-flex justify-content-center align-items-center radius-16 bg-base">
                    <img src="assets/images/pricing/price-icon1.png" alt="">
                </span>
                <div class="">
                    <!-- <span class="fw-medium text-md text-secondary-light">With Revenue Sharing</span> -->
                    <h6 class="mb-0" style="color:#f6f6f7;">{{$allsub->subscription_name}}</h6>
                </div>
                <div>
                    <!-- <a>
                      <iconify-icon style="color:#fff;" icon="weui:setting-outlined" width="24" height="24"></iconify-icon>
                    </a> -->
                    <div class="dropdown">
                            <button style="position: relative;
    left: 0px;" class="btn px-18 py-11 text-primary-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <iconify-icon style="color:#fff;" icon="weui:setting-outlined" width="24" height="24"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu" style="">
                              <li><a style="color:#000;" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900" href="{{route('edit_subscription',['id'=>$allsub->id])}}">Edit</a></li>
                              <!-- <li><a style="color:#000;" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900" href="#">Activate</a></li>
                              <li><a style="color:#000;" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900" href="#">Deactive</a></li> -->
                            </ul>
                        </div>
                </div>
            </div>
            <p class="mt-16 mb-0 text-secondary-light mb-28">{{$allsub->plan_info_text}}</p>
            <!-- <h3 class="mb-24" style="color:#f6f6f7;">$99 <span class="fw-medium text-md text-secondary-light">/monthly</span> </h3> -->
            <h3 class="mb-24" style="color:#f6f6f7;font-size: 33px !important;">
                <?php
                   $curr = DB::table('currency')->where('code',$allsub->currency)->first();
                   echo $curr->symbol;
                 ?>
                {{$allsub->subscription_amount}}
            </h3>
            <span class="mb-20 fw-medium" style="color:#f6f6f7;">What’s included</span>
            <ul>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->artist_no}} Artist</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->no_of_tracks}} Tracks</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->no_of_products}} Products</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">{{$allsub->subscription_limit_per_year}} &nbsp; Limit/Year</span>
                </li>
                <li class="d-flex align-items-center gap-16 mb-16">
                    <span class="w-24-px h-24-px d-flex justify-content-center align-items-center bg-lilac-600 rounded-circle"><iconify-icon icon="iconamoon:check-light" class="text-white text-lg "></iconify-icon></span>
                    <span class="text-secondary-light text-lg">Duration &nbsp; {{$allsub->subscription_duration}}</span>
                </li>
            </ul>
            <button type="button" class="bg-lilac-600 bg-hover-lilac-700 text-white text-center border border-lilac-600 text-sm btn-sm px-12 py-10 w-100 radius-8 mt-28" data-bs-toggle="modal" data-bs-target="#exampleModal">Get started</button>
        </div>
    </div>
@endforeach  