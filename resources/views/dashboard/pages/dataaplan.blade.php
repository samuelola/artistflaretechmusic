                    @foreach($plans as $plan)
                          <tr>
                              <td>{{$plan->subscription_name ?? ""}}</td> 
                              <td>{{$plan->artist_no ?? ""}}</td>
                              <td>{{$plan->stock_keeping_unit ?? ""}}</td>
                              <td>{{$plan->subscription_duration ?? ""}}</td>
                              <td>{{$plan->no_of_tracks ?? ""}}</td>
                              <td>{{$plan->no_of_products ?? ""}}</td>
                              <td>
                                  @foreach(json_decode($plan->subscription_for) as $vall)
                                    
                                    <span class="badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{$vall}}</span>
                                  @endforeach
                              </td>
                              <td>{{$plan->track_file_quality ?? ""}}</td>
                              <td>
                                  <?php 
                                      $u = \DB::table('currency')->where('code',$plan->currency)->first();
                                      echo $u->symbol;
                                  ?>
                              </td>
                              <td>
                                  <?php 
                                      $u = \DB::table('currency')->where('code',$plan->currency)->first();
                                      echo $u->country;
                                  ?>
                              </td>
                              <td>{{$plan->subscription_amount ?? ""}}</td>
                              <td>{{$plan->include_tax ?? ""}}</td>
                              <td>{{$plan->plan_info_text ?? ""}}</td>
                              <td>{{$plan->subscription_limit_per_year ?? ""}}</td>
                              <td>{{$plan->is_this_free_subscription ?? ""}}</td>
                              <td>{{$plan->is_cancellation_enable ?? ""}}</td>
                              <td>{{$plan->is_one_time_subscription ?? ""}}</td> 
                          </tr>
                    @endforeach