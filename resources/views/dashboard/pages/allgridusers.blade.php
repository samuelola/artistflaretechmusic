@foreach($get_all_users as $rel)
                      <div class="col-xxl-6 col-sm-6 mt-3">
                            <div class="card h-100 radius-12" style="border: 1px solid #e8dede;">
                                <div class="card-body p-24">
                                    <div class="w-64-px h-64-px d-inline-flex align-items-center justify-content-center bg-gradient-purple text-lilac-600 mb-16 radius-12">
                                        <!-- <iconify-icon icon="solar:medal-ribbons-star-bold" class="h5 mb-0"></iconify-icon>  -->
                                        <iconify-icon icon="si:user-line" width="24" height="24"></iconify-icon>
                                    </div>
                                    <h6 class="mb-8">{{$rel->first_name}} {{$rel->last_name}}</h6>
                                    <p class="card-text mb-8">{{$rel->email}}</p>
                                    <p class="card-text mb-8">{{ \Carbon\Carbon::parse($rel->join_date)->format('d/m/Y')}}</p>
                                    <p class="card-text mb-8">No of Albums : {{$rel->albums ?? ''}}</p>
                                    <p class="card-text mb-8">No of Tracks : {{$rel->tracks ?? ''}}</p>
                                    <p class="card-text mb-8">
                                        Language :
                                        <?php 
                                            $lang = \DB::table('languages')->where('iso',$rel->language)->first();
                                            echo $lang->name ?? '';
                                        ?>
                                    </p>
                                    <p class="card-text mb-8">
                                        State :
                                        <?php 
                                            $state = \DB::table('states')->where('id',$rel->state)->first();
                                            if(is_null($state)){
                                                
                                                echo "Not Available";
                                            }else{
                                                echo $state->name ?? '';
                                            }
                                        ?>
                                    </p>
                                    <p class="card-text mb-8">
                                            <?php
                               
                                                if($rel->active == 'Yes'){
                                                    ?><span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Active</span> <?php
                                                }elseif($rel->active == 'No'){
                                                    ?><span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Not Active</span> <?php
                                                }
                                            ?>
                                    </p> 
                                    <p class="card-text mb-8">
                                    <?php 
                                      $encrypted = encrypt($rel->id);
                          
                           
                                    ?>
                                            <div style="float:left;margin-right: 8px;margin-top:15px;">
                                            <a href="{{route('viewdashboardusers',$encrypted)}}">
                                                <iconify-icon icon="mage:edit" data-toggle="tooltip" title='Edit' width="24" height="24" style="color:#700084;"></iconify-icon>
                                            </a>
                                            </div>
                                            
                                            <div style="margin-top:23px;">
                                                <form method="POST" action="{{route('deleteUser',$encrypted)}}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <iconify-icon class="show_confirm" data-toggle="tooltip" title='Delete' icon="mdi-light:delete" width="24" height="24" style="color:red;"></iconify-icon>
                                                </form>
                                            </div>
                                    </p> 

                                </div>
                            </div>
                       </div>
                       @endforeach 