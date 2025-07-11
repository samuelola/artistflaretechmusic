        @foreach($get_all_users as $rel)
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <?php 
                                 if(is_null($rel->profile_image)){
                                     ?><img src="assets/images/users/user1.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden"><?php  
                                 }else{
                                     ?><img src="/profile_uploads/{{$rel->profile_image}}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden"><?php
                                 }
                              ?>
                              
                              <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-medium">{{$rel->first_name ?? ""}}{{$rel->last_name ?? ""}}</h6>
                                <span class="text-sm fw-medium">{{$rel->email ?? ""}}</span>
                              </div>
                            </div>
                          </td>
                          <td>{{ \Carbon\Carbon::parse($rel->join_date)->format('d/m/Y')}}</td>
                          <td>{{$rel->albums ?? ""}}</td>
                          <td>{{$rel->tracks ?? ""}}</td>
                          <td>
                              <?php 
                                 $lang = \DB::table('languages')->where('iso',$rel->language)->first();
                                 echo $lang->name ?? "";
                              ?>
                          </td>
                          <td>
                             <?php 
                                 $country = \DB::table('countries')->where('iso2',$rel->country)->first();
                                 echo $country->name ?? "";
                              ?>
                          </td>
                          <td>
                             <?php 
                                 $state = \DB::table('states')->where('id',$rel->state)->first();
                                 if(is_null($state)){
                                    
                                    echo "Not Available";
                                 }else{
                                    echo $state->name ?? "";
                                 }
                              ?>
                          </td>
                          <td class="text-center"> 
                            <?php
                               
                                if($rel->active == 'Yes'){
                                  ?><span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Active</span> <?php
                                }elseif($rel->active == 'No'){
                                   ?><span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Not Active</span> <?php
                                }
                            ?>
                            
                          </td>
                          <td>
                           <?php 
                             $encrypted = encrypt($rel->id);
                          
                           
                           ?>
                           <div style="float:left;margin-right: 8px;">
                           <a href="{{route('viewdashboardusers',$encrypted)}}">
                              <!-- <span class="badge text-sm fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle bg-primary-600  text-white">1</span> -->
                              <!-- <span class="badge text-sm fw-semibold  justify-content-center align-items-center rounded-circle bg-primary-600  text-white">
                              
                              </span> -->
                              <iconify-icon icon="mage:edit" data-toggle="tooltip" title='Edit' width="24" height="24" style="color:#700084;"></iconify-icon>
                           </a>
                           </div>
                          <div>
                          <form method="POST" action="{{route('deleteUser',$encrypted)}}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <!-- <span class="badge text-sm fw-semibold border show_confirm border-danger-600 text-danger-600 bg-transparent px-20 py-9 radius-4 text-white">Delete</span> -->
                            <!-- <span class="badge text-sm fw-semibold  justify-content-center align-items-center rounded-circle bg-primary-600  text-white">1</span> -->
                            <iconify-icon class="show_confirm" data-toggle="tooltip" title='Delete' icon="mdi-light:delete" width="24" height="24" style="color:red;"></iconify-icon>
                           </form>
                          </div>
                          </td>
                        </tr>
@endforeach