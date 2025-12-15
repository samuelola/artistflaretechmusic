@extends('dashboard.index')
@section('title')
   Payment
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
  <h6 class="fw-semibold mb-0">Payments</h6>
  
</div>

   
    <div class="card h-100 radius-12" style="
    height: 400px;
">
       <div class="card-body p-24">
           <div class="row">
               <div class="col-md-5">
                        <div class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div>
                                    <span class="mb-2 fw-medium text-md">Total Earnings (USD)</span>
                                    <h6 class="fw-semibold" style="margin-top: 20px;
    margin-bottom: 10px !important;">$0.00</h6>
                                </div>
                                <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-primary-100 text-primary-600">
                                    
                                    <iconify-icon icon="streamline-freehand:cash-payment-bag-1" width="24" height="24"></iconify-icon>
                                </span>
                                <div class="d-flex gap-16">
                                        <button class="btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2 px-32" type="submit">Withdraw<iconify-icon icon="ph:hand-withdraw-light" width="24" height="24"></iconify-icon> </button>
                                        <button class="btn btn-primary-600 flex-shrink-0 d-flex align-items-center gap-2 px-32" type="submit">Transfer <i class="ri-send-plane-fill"></i> </button>
                                    </div>
                            </div>
                            
                        </div>
                </div>
                <!-- <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="px-20 py-16 shadow-none radius-8 h-100 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div>
                                <span class="mb-2 fw-medium text-md">Total Purchase</span>
                                <h6 class="fw-semibold mb-1">$35,000</h6>
                            </div>
                            <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                               <iconify-icon icon="streamline-freehand:cash-payment-bag-1" width="24" height="24"></iconify-icon>
                            </span>
                        </div>
                        <p class="text-sm mb-0">
                            <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">
                                </span>  </p>
                    </div>
                </div> -->

                 <h6 style="margin-top:25px;">Products</h6>
                 <div class="col-md-12">
                   
                    @if($product_count > 0)
                    <div class="table-responsive" style="margin-top:20px">
                        <table class="table basic-border-table mb-0">
                            <thead>
                            <tr>
                                <th>Sn </th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Artist</th>
                                <th>Tracks</th>
                                <th>Format</th>
                                <th>Label</th>
                                <th>Status</th>
                                <th>Split Sheet</th>

                            </tr>
                            </thead>
                             <tbody id="productsTableBody">
                                @php
                                $sn = 1;
                                @endphp
                                @foreach($release_products as $item)
                                   <tr>
                                      <td>{{ $release_products->firstItem() + $loop->index }}</td>
                                      @php
                                       $artworkPath = $item->artworks->first()->path ?? 'default.jpg';
                                       $storageUrl = rtrim(config('app.website_storage_link'), '/');
                                      @endphp
                                      <td><img src="{{ $storageUrl . '/storage/' . ltrim($artworkPath, '/') }}" class="card-img-top" alt="" style="height:100px;width:100px"></td>
                                      <td>
                                         {{$item->title ?? ''}}
                                      </td>
                                      <td>
                                         {{$item->user->first_name ?? ''}} {{$item->user->last_name ?? ''}}
                                      </td>
                                      <td>
                                        {{$item->tracks_count ?? 0}}
                                      </td>
                                      <td>
                                         {{$item->release_type ?? ''}}
                                      </td>
                                      <td>
                                         {{$item->label_name ?? ''}}
                                      </td>
                                      <td>
                                        @if($item->distributed == 'yes')
                                         Released
                                        @elseif($item->distributed == 'no')  
                                         Not Released
                                        @endif 
                                      </td>
                                      <td>
                                            <a href="javascript:void(0)"
                                            class="btn btn-sm btn-primary open-split-sheet"
                                            data-id="{{ $item->id }}"
                                            data-title="{{ $item->title }}">
                                                Split Sheet
                                            </a>
                                        </td>
                                     
                                   </tr>
                                @endforeach
                            </tbody>
                        </table>
                         @if($release_products->hasMorePages())
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary" id="loadMoreProducts"
                                    data-next-page="{{ $release_products->nextPageUrl() }}">
                                Load More
                            </button>
                        </div>
                        @endif
                        </div>
                        @else
                          <p class="text-center"> No Products</p>
                        @endif
               </div>

               
              
               <h6 style="margin-top:25px;">Transactions</h6>
               @if($payments->count())
               <div class="col-md-12">
                    <div class="table-responsive" style="margin-top:20px">
                        <table class="table basic-border-table mb-0">
                            <thead>
                            <tr>
                                <th>Sn </th>
                                <th>Payment To</th>
                                <th>Paid Amount</th>
                                <th>Currency</th>
                                <th>Payment Method</th>
                                <th>Deduction</th>
                                <th>status</th>
                                <th>Payout Date</th>

                            </tr>
                            </thead>
                            <tbody>
                                @php
                                $sn = 1;
                                @endphp
                                @foreach($payments as $payment)
                                   <tr>
                                      <td>{{$sn++}}</td>
                                      <td>{{$payment->user->first_name ?? ''}}</td>
                                      <td>
                                         {{$payment->amount ?? ''}}
                                      </td>
                                      <td>
                                         {{$payment->currency ?? ''}}
                                      </td>
                                      <td>
                                         {{$payment->payment_method ?? ''}}
                                      </td>
                                      <td>
                                         {{$payment->deduction ?? ''}}
                                      </td>
                                      <td>
                                         {{$payment->status ?? ''}}
                                      </td>
                                      <td>
                                         {{$payment->payout_date ?? ''}}
                                      </td>
                                     
                                   </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
               </div>
               @else
                  <p class="text-center"> No Transactions</p>
               @endif
           </div> 
       </div>
    </div>

    

<!-- Split Sheet Modal -->
<div class="modal fade" id="splitSheetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Split Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Product:</strong> <span id="splitSheetTitle"></span></p>

                <!-- Your split sheet content -->
                <div id="splitSheetContent">
                    <p>Loading split sheet details...</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
   

<script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
</script>

<script>
        $(document).ready(function() {
            $('.js-example-basic-singleet').select2({
                width: 'resolve'
            });
        });
</script>


<script>
    $(document).ready(function() {
        $("#AssignPermissionRoleForm").click(function(e){
            e.preventDefault();
            // $("#AssignPermissionRoleForm").prop('disabled',true)
            var role_id = $("#role_id").val();
            var permission_id = $("#permission_id").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('create_permission_role')}}",
                type: "POST",
                data : {role_id:role_id,permission_id:permission_id},
                success:function(response){
                    $("#AssignPermissionRoleForm").prop('disabled',false)
                    if(response.success){
                       alert(response.msg)
                       $('#assignPermissionRoleModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg)
                    }
                }
            })
        })


        $('.deletePermissionBtn').click(function(){
          var permissionId = $(this).data("id");
          var permissionName = $(this).data("name");

          //$('.delete-permission').text(permissionName);
          $('#deletePermissionId').val(permissionId);

        });

        $("#deleteFormPermissionRoles").click(function(e){
            e.preventDefault();
            //$("#deleteFormPermissionRoles").prop('disabled',true)
            var deletepermission = $("#deletePermissionId").val();
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('delete_permission_role')}}",
                type: "POST",
                data : {permission_id:deletepermission},
                success:function(response){
                    //$("#deleteFormPermissionRoles").prop('disabled',false)
                    if(response.success){
                       alert(response.msg)
                       $('#deletePermissionModal').modal('hide');
                       location.reload();
                    }else{
                       alert(response.msg);
                    }
                }
            })
        })
        
    }); 

</script>

<script>
$(document).ready(function () {

    const splitSheetModal = new bootstrap.Modal(
        document.getElementById('splitSheetModal')
    );

    $(document).on('click', '.open-split-sheet', function () {

    const productId = $(this).data('id');
    const title = $(this).data('title');

    $('#splitSheetTitle').text(title);
    $('#splitSheetContent').html('<p>Loading tracks...</p>');

    $.ajax({
        url: `/split-sheet/${productId}`,
        type: 'GET',
        success: function (tracks) {

            let html = '';

            if (tracks.length === 0) {
                html = '<p>No tracks found.</p>';
            } else {
                html += `<table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Track</th>
                                    <th>Participants</th>
                                </tr>
                            </thead>
                            <tbody>`;

                tracks.forEach(track => {
                    html += `<tr>
                                <td>${track.title}</td>
                                <td>`;

                    if (track.participants.length) {
                        track.participants.forEach(p => {
                            html += `<div>${p.participant} – ${p.payout}%</div>`;
                            
                            
                        });
                        
                    } else {
                        html += 'No participants';
                    }

                    html += `</td></tr>`;
                });

                html += `</tbody></table>`;
            }

            $('#splitSheetContent').html(html);
            splitSheetModal.show();
        },
        error: function () {
            $('#splitSheetContent').html('<p class="text-danger">Failed to load tracks.</p>');
        }
    });
});

});
</script>


<script>
$(document).on('click', '#loadMoreProducts', function () {

    let button = $(this);
    let nextPageUrl = button.data('next-page');

    button.text('Loading...').prop('disabled', true);

    $.ajax({
        url: nextPageUrl,
        type: 'GET',
        success: function (response) {

            let rows = $(response).find('#productsTableBody').html();
            let newNextPage = $(response).find('#loadMoreProducts').data('next-page');

            $('#productsTableBody').append(rows);

            if (newNextPage) {
                button
                    .data('next-page', newNextPage)
                    .text('Load More')
                    .prop('disabled', false);
            } else {
                button.remove();
            }
        },
        error: function () {
            button.text('Load More').prop('disabled', false);
            alert('Failed to load more products.');
        }
    });
});
</script>


@endsection
  





