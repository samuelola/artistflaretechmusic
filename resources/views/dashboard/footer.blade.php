<footer class="d-footer">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <p class="mb-0">© 2025 FlareTechMusic. All Rights Reserved.</p>
    </div>
    <!-- <div class="col-auto">
      <p class="mb-0">Made by <span class="text-primary-600">wowtheme7</span></p>
    </div> -->
  </div>
</footer>
</main>

  <!-- jQuery library js -->
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
  <script src="{{asset('assets/js/lib/jquery-3.7.1.min.js')}}"></script>
  <!-- Bootstrap js -->
  <script src="{{asset('assets/js/lib/bootstrap.bundle.min.js')}}"></script>
  
  
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

  <!-- Apex Chart js -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- <script src="assets/js/lib/apexcharts.min.js"></script> -->
  <!-- Data Table js -->
  <!-- <script src="{{asset('datatables.js')}}"></script> -->
  <!-- Iconify Font js -->
  <script src="{{asset('assets/js/lib/iconify-icon.min.js')}}"></script>
  <!-- jQuery UI js -->
  <script src="{{asset('assets/js/lib/jquery-ui.min.js')}}"></script>
  <!-- Vector Map js -->
  <script src="{{asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
  <script src="{{asset('assets/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
  <!-- Popup js -->
  <script src="{{asset('assets/js/lib/magnifc-popup.min.js')}}"></script>
  <!-- Slick Slider js -->
  <script src="{{asset('assets/js/lib/slick.min.js')}}"></script>
  <!-- prism js -->
  <script src="{{asset('assets/js/lib/prism.js')}}"></script>
  <!-- file upload js -->
  <script src="{{asset('assets/js/lib/file-upload.js')}}"></script>
  <!-- audioplayer -->
  <script src="{{asset('assets/js/lib/audioplayer.js')}}"></script>
  
  <!-- main js -->
  <script src="{{asset('assets/js/app.js')}}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  

<!-- <script src="assets/js/homeOneChart.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 

<script>
   var options = {
  chart: {
    type: 'line'
  },
  series: [{
    name: 'sales',
    data: [30,40,35,50,49,60,70,91,125]
  }],
  xaxis: {
    categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
  }
}

var chart = new ApexCharts(document.querySelector("#chartt"), options);

chart.render();
</script>  

 


<script>
    var ENDPOINT = "{{ route('dashboard') }}";
    var page = 1;
  
    $(".load-more-data").click(function(){
        page++;
        infinteLoadMore(page);
    });
    
  
    /*------------------------------------------
    --------------------------------------------
    call infinteLoadMore()
    --------------------------------------------
    --------------------------------------------*/
    function infinteLoadMore(page) {
        $.ajax({
                url: ENDPOINT + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-load').show();
                }
            })
            .done(function (response) {

                console.log(response.html);
                if (response.html == '') {
                    $('.auto-load').html("We don't have more data to display :(");
                    return;
                }

                $('.auto-load').hide();
                $("#data-wrapper").append(response.html);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    }
</script> 


<script>
    var ENDPOINT = "{{ route('dashboard') }}";
    var page = 1;
  
    $(".load-more-dataa").click(function(){
        page++;
        infinteLoadMorer(page);
    });
    
  
    /*------------------------------------------
    --------------------------------------------
    call infinteLoadMore()
    --------------------------------------------
    --------------------------------------------*/
    function infinteLoadMorer(page) {
        $.ajax({
                url: ENDPOINT + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-loadd').show();
                }
            })
            .done(function (response) {

                console.log(response.newhtml);
                if (response.newhtml == '') {
                    $('.auto-loadd').html("We don't have more data to display :(");
                    return;
                }

                $('.auto-loadd').hide();
                $("#data-wrapperr").append(response.newhtml);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    }
</script>


  



<script>
  // ================================ Total Subscriber bar chart Start ================================ 

  <?php 
    $users = \App\Models\User::distinct('first_name')->count();
    $total_subsscribe = \DB::table("subscription_payment_details")->distinct('email')->count();
    $total_tracks = \DB::table("trackdetails")->where(['ReleaseCount'=>0,'ReleaseCount'=>1])->distinct('UserName')->count();
    $total_albums = \DB::table("users")->sum('albums');
    $total_albumss = (int)$total_albums;
    $total_labels = \DB::table("labeldetails")->count();
    $total_labelss = (int)$total_labels;
                   
    $Er = ['users'=>$users,'subscribers'=>$total_subsscribe,'tracks'=>$total_tracks,'albums'=>$total_albumss,'singles'=>$total_labelss];
    $cat = ['users','subscribers','tracks','albums','singles'];
    $formattedt = [];

    foreach ($Er as $key=>$value) {
        $formattedt[] = [
            'x' => $key,
            'y' => $value,
        ];
    }

       $formattedft = json_encode($formattedt );
                 
  ?>
  var options = {
      // series: [{
      //     name: "Sales",
      //     data: [{
      //         "x": "Sun",
      //         "y": "15",
      //     }, {
      //         "x": "Mon",
      //         "y": "12",
      //     }, {
      //         "x": "Tue",
      //         "y": "18",
      //     }, {
      //         "x": "Wed",
      //         "y": "20",
      //     }, {
      //         "x": "Thu",
      //         "y": "13",
      //     }, {
      //         "x": "Fri",
      //         "y": "16",
      //     }, {
      //         "x": "Sat",
      //         "y": "6",
      //     }]
      // }],

     

      series: [{
          name: "Sales",
          data: <?php
          
          foreach((array)$formattedft as $rrr){
            echo $rrr;
           }
          ?>
      }],


      chart: {
          type: 'bar',
          height: 235,
          toolbar: {
              show: false
          },
      },
      plotOptions: {
          bar: {
            borderRadius: 6,
            horizontal: false,
            columnWidth: 24,
            columnWidth: '52%',
            endingShape: 'rounded',
          }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
          type: 'gradient',
          colors: ['#ce11e7'], // Set the starting color (top color) here
          gradient: {
              type: 'vertical',  // Gradient direction (vertical)
              stops: [0, 100],
          },
      },
      grid: {
          show: true,
          borderColor: '#D1D5DB',
          strokeDashArray: 4, // Use a number for dashed style
          position: 'back',
          padding: {
            top: -10,
            right: -10,
            bottom: -10,
            left: -10
          }
      },
      xaxis: {
          type: 'category',
          categories: [
                      <?php 
                          foreach ($cat as $value) {
                            echo "'$value',";
                          }
                       ?>
                ]
      },
      yaxis: {
        show: true,
      },
  };

  var chart = new ApexCharts(document.querySelector("#barChart"), options);
  chart.render();
  // ================================ Total Subscriber bar chart End ================================ 
</script>

<script>

// ================================ Users Overview Donut chart Start ================================ 

<?php

$plan_count = \DB::table('subscription_payment_details')
     ->select(\DB::raw('count(*) as sub_count'))           
     ->groupBy('Planname')
     ->get();
     $planname = \DB::table('subscription_payment_details')
     ->select(\DB::raw('Planname'))           
     ->groupBy('Planname')
     ->get();

     $countvalue = [];              
     foreach($plan_count as $dd){
         $countvalue[] = $dd->sub_count;
     }

     $planvalue = [];              
     foreach($planname as $dd){
         $planvalue[] = $dd->Planname;
     }

     $c=array_combine($planvalue,$countvalue);    
  

?>

var options = { 
      series: [
        <?php 
               foreach ($countvalue as $key => $valuey) {
                  echo "$valuey,";
               }
             ?>
      ],
      colors: ['#FF9F29', '#487FFF', '#1E3A8A'],
      labels: [
        <?php 
               foreach ($planvalue as $key => $value) {
                  echo "'$value',";
              }
        ?>
        
      ] ,
      legend: {
          show: true
      },
      chart: {
        type: 'donut',    
        height: 270,
        sparkline: {
          enabled: true // Remove whitespace
        },
        margin: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        },
        padding: {
          top: 0,
          right: 0,
          bottom: 0,
          left: 0
        }
      },
      stroke: {
        width: 0,
      },
      dataLabels: {
        enabled: false
      },
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }],
    };

    var chart = new ApexCharts(document.querySelector("#userOverviewDonutChart"), options);
    chart.render();
  // ================================ Users Overview Donut chart End ================================ 

</script>

<script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                width: 'resolve'
            });
        });
        $(document).ready(function() {
            $('.js-example-basic-singlee').select2({
                width: 'resolve'
            });
        });
</script>



<script>
    
    $(document).ready(function() {
       $('#listView').click(function() {
         $(".gridView").hide();
         $(".listView").show();
       });
   });
</script>
<script>
   
    $(document).ready(function() {
       $('#gridView').click(function() {
           $(".gridView").show();
           $(".listView").hide();
       });
   });
</script>

<script>
    var ENDPOINT = "{{ route('allUser') }}";
    var page = 1;
  
    $(".load-more-alluserdata").click(function(){
          page++;
          infinteLoadMoreralluser(page);

    });
    
  
    /*------------------------------------------
    --------------------------------------------
    call infinteLoadMore()
    --------------------------------------------
    --------------------------------------------*/
    function infinteLoadMoreralluser(page) {
        $.ajax({
                url: ENDPOINT + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-loadalluser').show();
                }
            })
            .done(function (response) {

                console.log(response);
                if (response.htmluserdata == '') {
                    $('.auto-loadalluser').html("We don't have more data to display :(");
                    return;
                }

                $('.auto-loadalluser').hide();
                $("#data-alluser").append(response.htmluserdata);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    }
</script>

<script>
    var ENDPOINT = "{{ route('allUser') }}";
    var page = 1;
  
    $(".load-more-allgriduserdata").click(function(){
        page++;
        infinteLoadMorerallgrid(page);
    });
    
  
    /*------------------------------------------
    --------------------------------------------
    call infinteLoadMore()
    --------------------------------------------
    --------------------------------------------*/
    function infinteLoadMorerallgrid(page) {
        $.ajax({
                url: ENDPOINT + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-loadallgriduser').show();
                }
            })
            .done(function (response) {

                console.log(response.htmlusergriddata);
                if (response.htmlusergriddata == '') {
                    $('.auto-loadallgriduser').html("We don't have more data to display :(");
                    return;
                }

                $('.auto-loadallgriduser').hide();
                $("#data-allgriduser").append(response.htmlusergriddata);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script type="text/javascript">

 
     $('.show_confirm').click(function(event) {

          var form =  $(this).closest("form");

          var name = $(this).data("name");

          event.preventDefault();

          swal({

              title: `Are you sure you want to delete this record?`,

              text: "it will be deleted",

              icon: "warning",

              buttons: true,

              dangerMode: true,

          })

          .then((willDelete) => {

            if (willDelete) {

              form.submit();

            }

          });

      });

</script>

<script>

var ENDPOINT = "{{ route('allsubscription') }}";

var page = 1;



$(window).scroll(function () {

    if ($(window).scrollTop() + $(window).height() >= ($(document).height() - 20)) {

        page++;

        infinteLoadMoreallSub(page);

    }

});


function infinteLoadMoreallSub(page) {

    $.ajax({

            url: ENDPOINT + "?page=" + page,

            datatype: "html",

            type: "get",

            beforeSend: function () {

                $('.auto-loadallsub').show();

            }

        })

        .done(function (response) {

            if (response.htmlallsub == '') {

                $('.auto-loadallsub').html("We don't have more data to display :(");

                return;

            }



            $('.auto-loadallsub').hide();

            $("#data-wrapperallsub").append(response.htmlallsub);

        })

        .fail(function (jqXHR, ajaxOptions, thrownError) {

            console.log('Server error occured');

        });

}

</script>
<script>
    var ENDPOINT = "{{ route('dashboard') }}";
    var page = 1;
  
    $(".load-more-dataaplan").click(function(){
        page++;
        infinteLoadMorerplan(page);
    });
    
  
    /*------------------------------------------
    --------------------------------------------
    call infinteLoadMore()
    --------------------------------------------
    --------------------------------------------*/
    function infinteLoadMorerplan(page) {
        $.ajax({
                url: ENDPOINT + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-loaddplan').show();
                }
            })
            .done(function (response) {

                console.log(response.newhtmlplan);
                if (response.newhtmlplan == '') {
                    $('.auto-loaddplan').html("We don't have more data to display :(");
                    return;
                }

                $('.auto-loaddplan').hide();
                $("#data-wrapperrplan").append(response.newhtmlplan);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured');
            });
    }
</script>

<script>
   $(document).ready( function () {
    $('#myTable').DataTable({order:[]});
} );
</script>




@yield('script')

</body>
</html>