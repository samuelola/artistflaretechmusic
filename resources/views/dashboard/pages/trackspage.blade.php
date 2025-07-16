@foreach($all_th_tracks as $all_th_track)
            <div class="col-xl-6">
                <div style="border:1px solid #700084 !important;" class="card radius-12 overflow-hidden h-100 d-flex align-items-center flex-nowrap flex-row">
                    <div class="d-flex flex-shrink-0 w-170-px h-100">
                        <img src="{{asset('assets/images/card-component/walking3.jpg')}}" class="h-100 w-100 object-fit-cover" alt="">
                    </div>
                    <div class="card-body p-16 flex-grow-1">
                        <h5 class="card-title text-lg text-primary-light mb-6">{{$all_th_track->TrackName}}</h5>
                        <p class="card-text text-neutral-600 mb-16">By {{$all_th_track->UserName}}</p>
                        <a href="{{route('view_tracks',$all_th_track->id)}}" class="btn btn-sm btn-primary-610 radius-8 d-inline-flex align-items-center gap-1">
                            view
                            <!-- <iconify-icon icon="f7:paperplane"></iconify-icon> -->
                            <iconify-icon icon="lets-icons:view-light" width="16" height="16"></iconify-icon>
                        </a>
                        <div style="display: inline;display: inline;
    margin-left: 170px;
    position: relative;
    top: -70px;">
                            <a href="#">
                                <iconify-icon style="color:#700084;" icon="material-symbols-light:download" width="30" height="30"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach    