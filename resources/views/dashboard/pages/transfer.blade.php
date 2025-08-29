@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')

@include('sweetalert::alert')

 

<main class="dashboard-main">
  <div class="navbar-header">
    <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search">
          <input type="text" name="search" placeholder="Search">
          <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
        </form>
      </div>
    </div>
    @include('dashboard.subheader')
    </div>
  </div> 
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <!-- <h6 class="fw-semibold mb-0">All Subscriptions</h6> -->

</div>

        <div class="row">
                <div class="col-md-12">
                        @if(session('error')) 
                            <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        {!! session('error') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(session('success'))  
                            <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        {!! session('success') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>  
                        @endif
                </div>
                
        </div>

   
            <!--new row -->
               <div class="row gy-4 mt-3">
                
                <div class="col-xxl-12">
            <div class="card p-0 overflow-hidden position-relative radius-12 h-100">
                <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                    <h6 class="text-lg mb-0">Transfer</h6>
                </div>
                <div class="card-body p-24 pt-10">
                    <ul class="nav button-tab nav-pills mb-16" id="pills-tab-four" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10 active" id="pills-button-icon-home-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-home" type="button" role="tab" aria-controls="pills-button-icon-home" aria-selected="false" tabindex="-1">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="text-xl"></iconify-icon>
                            <span class="line-height-1">To Bank</span>
                          </button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10" id="pills-button-icon-details-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-details" type="button" role="tab" aria-controls="pills-button-icon-details" aria-selected="false" tabindex="-1">
                            <iconify-icon icon="hugeicons:folder-details" class="text-xl"></iconify-icon>
                            <span class="line-height-1">To Another Wallet</span>
                          </button>
                        </li>
                        
                    </ul>
                    <div class="tab-content" id="pills-tab-fourContent">
                        <div class="tab-pane fade active show" id="pills-button-icon-home" role="tabpanel" aria-labelledby="pills-button-icon-home-tab" tabindex="0">
                            <div class="align-items-center gap-3">
                                 <!--put form here-->
                                 <div class="row">

                                      <div class="col-md-3"></div>
                                      <div class="col-md-6">
                                            <form method="post" action="{{route('transfer_payment')}}">
                                                @csrf
                                                <div class="col-12">
                                                <label class="form-label">Enter Amount</label>
                                                <input type="number" name="amount" class="form-control">
                                                @error('amount')
                                                        <p class="text-red-500 text-sm" style="color:#d22f2f">{{ $message }}</p>
                                                @enderror
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <label class="form-label">Account Number</label>
                                                    <input id="account_number" type="text" name="account_number" class="form-control">
                                                    @error('account_number')
                                                        <p class="text-red-500 text-sm" style="color:#d22f2f">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                               

                                                <div class="col-12 mt-3" id="the_bank">
                                                    <label class="form-label">Bank</label>
                                                    <select id="bank" name="bank" class="form-control js-example-basic-single" style="width: 100% !important">
                                                        <option>--Select Bank--</option>
                                                       @foreach($rels as $val)
                                                          <option value="{{$val->code}}">{{$val->name}}</option>
                                                       @endforeach
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-12 mt-3" id="account_parent">
                                                    <label class="form-label">Account Name</label>
                                                    <input id="account_name" type="text" name="account_name" class="form-control" readonly>
                                                    <i class="fa fa-spinner fa-spin input-loader" id="inputLoader"></i>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <label class="form-label">Reason (optional)</label>
                                                    <input type="text" name="reason" class="form-control">
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button type="submit" class="btn btn-primary-600">
                                                      Send
                                                    </button>
                                                </div>

                                            </form>
                                      </div>
                                      <div class="col-md-3"></div>
                                      
                                  </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-button-icon-details" role="tabpanel" aria-labelledby="pills-button-icon-details-tab" tabindex="0">
                              <div class=" align-items-center">
                                      <div class="row">

                                          <div class="col-md-3"></div>
                                          <div class="col-md-6">
                                                <form method="post" action="{{route('user_wallet_transfer')}}">
                                                    @csrf
                                                    <div class="col-12">
                                                    <label class="form-label">Enter Amount</label>
                                                    <input type="number" name="amount_b" class="form-control" value="{{ old('amount_b') }}">

                                                    <!-- @if ($errors->formB->has('amount_b'))
                                                    <span class="text-danger" style="color:#d22f2f">{{ $errors->formA->first('amount_b') }}</span>
                                                    @endif -->
                                                    @error('amount_b')
                                                            <p class="text-red-500 text-sm" style="color:#d22f2f">{{ $message }}</p>
                                                    @enderror
                                                    </div>
                                                    
                                                    <div class="col-12 mt-3">
                                                        <label class="form-label">Add recipient</label>
                                                        <select name="recipient" class="form-control js-example-basic-single" style="width: 100% !important">
                                                            <option>--Select Recipient--</option>
                                                          @foreach($get_recipient as $val)
                                                              <option value="{{$val->user->id}}">{{$val->user->email}}</option>
                                                          @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                    
                                                    
                                                    <div class="col-12 mt-3">
                                                        <button type="submit" class="btn btn-primary-600">
                                                          Send
                                                        </button>
                                                    </div>

                                                </form>
                                          </div>
                                          <div class="col-md-3"></div>
                                      
                                  </div>
                              </div>
                        </div>
                        
                    </div>
                </div>  
            </div>
        </div>
                
            </div>
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
   

<script type="text/javascript">
    $(document).ready(function() {
        $('#the_bank').hide();
        $('#account_parent').hide();
        
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#account_number').keyup(function() {
            $('#the_bank').show();
            $('#account_parent').show();
        });
        
    });
</script>

<script>
   $(document).ready(function() {

      $('#bank').change(function() {
         var bank_code = $(this).val();
         var account_number = $("#account_number").val();
         $("#inputLoader").show();
         $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('resolve_account') }}",
                data: {bank_code:bank_code,account_number:account_number},
                type: "GET",
                success: function (response) {
                    const verified_data = response.data;
                    $('#account_name').val(verified_data.data.account_name); 
                },
                complete: function(){
                  $("#inputLoader").hide();
                },
                error: function (error) {
                    console.error('AJAX Error:', error);
                }
            });
      });
   });
</script>



@endsection



