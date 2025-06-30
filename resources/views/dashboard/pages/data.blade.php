                  
                      
                      
                       @foreach($subscribers as $subscriber)

                       
                       <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <img src="assets/images/users/user1.png" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                              <div class="flex-grow-1">
                                <h6 class="text-md mb-0 fw-medium">{{$subscriber->Firstname}}{{$subscriber->Lastname}}</h6>
                                <span class="text-sm text-secondary-light fw-medium">{{$subscriber->Email}}</span>
                              </div>
                            </div>
                          </td>
                          <td>{{$subscriber->Paymentdate}}</td>
                          <td>{{$subscriber->Invoicenumber}}</td>
                          <td>{{$subscriber->Country}}</td>
                          <td>
                          <?php 
                                if(empty($subscriber->MobileNumber) || is_null($subscriber->MobileNumber)){
                                  echo "Not Available";
                                }else{
                                  echo $subscriber->MobileNumber;
                                
                                }
                             ?>
                          </td>
                          <td>{{$subscriber->Partnername}}</td>
                          <td>
                          <?php 
                                if(empty($subscriber->Paidby) || is_null($subscriber->Paidby)){
                                  echo "Not Available";
                                }else{
                                  echo $subscriber->Paidby;
                                
                                }
                             ?>
                          </td>
                          <td>
                          <?php 
                                if(empty($subscriber->Planname) || is_null($subscriber->Planname)){
                                  echo "Not Available";
                                }else{
                                  echo $subscriber->Planname;
                                
                                }
                             ?>
                          </td>
                          <td>
                          <?php 
                                if(empty($subscriber->Amountpaid) || is_null($subscriber->Amountpaid)){
                                  echo "Not Available";
                                }else{
                                  echo $subscriber->Amountpaid;
                                }
                             ?>
                          </td>
                          <td>
                          <?php 
                                if(empty($subscriber->Paidthrough) || is_null($subscriber->Paidthrough)){
                                  echo "Not Available";
                                }else{
                                  echo $subscriber->Paidthrough;
                                }
                             ?>
                          </td>
                          <td>
                          <?php 
                                if(empty($subscriber->SubscriptionAmount) || is_null($subscriber->SubscriptionAmount)){
                                  echo "Not Available";
                                }else{
                                  echo $subscriber->SubscriptionAmount;
                                }
                             ?>
                          </td>
                          
                        </tr>
                       
                        
                        @endforeach
                        
                    