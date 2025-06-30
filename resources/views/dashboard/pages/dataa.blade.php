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
                                <h6 class="text-md mb-0 fw-medium">{{$rel->first_name}}{{$rel->first_name}}</h6>
                                <span class="text-sm text-secondary-light fw-medium">{{$rel->email}}</span>
                              </div>
                            </div>
                          </td>
                          <td>{{$rel->join_date}}</td>
                          <td>{{$rel->albums}}</td>
                          <td>{{$rel->tracks}}</td>
                          <td>
                              <?php 
                                 $lang = \DB::table('languages')->where('iso',$rel->language)->first();
                                 echo $lang->name;
                              ?>
                          </td>
                          <td>
                             <?php 
                                 $country = \DB::table('countries')->where('iso2',$rel->country)->first();
                                 echo $country->name;
                              ?>
                          </td>
                          <td>
                             <?php 
                                 $state = \DB::table('states')->where('id',$rel->state)->first();
                                 if(is_null($state)){
                                    
                                    echo "Not Available";
                                 }else{
                                    echo $state->name;
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
                        </tr>
           @endforeach