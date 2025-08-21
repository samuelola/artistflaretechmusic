@extends('dashboard.index')
@section('title')
   Analytics
@endsection
@section('content')

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
  <h6 class="fw-semibold mb-0">Analytics</h6>
  
</div>

        <div class="row">

           <!--start firstcolum-->
              <div class="col-lg-6">
            <div class="card h-100">
                    <div class="card-body">
                      <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <h6 class="text-lg mb-0">Top 5 Users</h6>
                        <!-- <select class="form-select bg-base form-select-sm w-auto radius-8">
                          <option>Yearly</option>
                          <option>Monthly</option>
                          <option>Weekly</option>
                          <option>Today</option>
                        </select> -->
                      </div>
                      <!-- <div class="d-flex flex-wrap align-items-center gap-2 mt-8">
                        <h6 class="mb-0">$27,200</h6>
                        <span class="text-sm fw-semibold rounded-pill bg-success-focus text-success-main border br-success px-8 py-4 line-height-1 d-flex align-items-center gap-1">
                          10% <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                        </span>
                        <span class="text-xs fw-medium">+ $1500 Per Day</span>
                      </div> -->
                     
                      <div id="charttt"></div>
                      
                      
                    </div>
              </div>
        </div>
           <!--end firstcolum-->
 
           <!--start secondcolum-->
               <div class="col-lg-6">
                      <div class="card h-100">
                              <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between">
                                  <h6 style="padding-top: 15px;
    padding-bottom: 15px;" class="text-lg mb-0">Top 5 Users Table</h6>
                                  <table class="table basic-border-table mb-0">
                                      <thead>
                                        <tr>
                                          <th>Name</th>
                                          <th>Count</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($topusers as $topuser)
                                        <tr>
                                          <td>{{$topuser->first_name}}</td>
                                          <td>{{$topuser->name_count}}</td>
                                         
                                        </tr>
                                        @endforeach
                                    
                                      </tbody>
                                    </table>
                                  
                                </div>
                                
                              </div>
                        </div>
                  </div>
           <!--end secondcolum-->

        </div>

        <div class="row" style="margin-top:20px">
              <div class=" col-xl-12">
                <div class="card h-100 radius-8 border-0 overflow-hidden">
                  <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                      <h6 class="mb-2 fw-bold text-lg">Top 5 Singles</h6>
                      
                    </div>


                    <div id="tracksOverviewDonutChart" class="apexcharts-tooltip-z-none"></div>

                  
                    
                  </div>
                </div>
              </div>
        </div>


        <div class="row" style="margin-top:20px">
            <div class="col-md-6">
                <div class="card h-100 radius-8 border-0 overflow-hidden">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                          <h6 class="mb-2 fw-bold text-lg">Top 5 Languages</h6>
                          
                        </div>
                        <div id="defaultLineChart" class="apexcharts-tooltip-style-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 p-0">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">Top 5 Languages Table</h6>
                    </div>
                    <div class="card-body p-24">
                        <table class="table basic-border-table mb-0">
                          <thead>
                            <tr>
                              <th>Language</th>
                              <th>Count</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($toplangs as $toplang)
                            <tr>
                              <td>{{$toplang->name}}</td>
                              <td>{{$toplang->language_count}}</td>
                              
                            </tr>
                            @endforeach
                        
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>    


       <div class="row" style="margin-top:20px">
              <div class=" col-xl-12">
                <div class="card h-100 radius-8 border-0 overflow-hidden">
                  <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                      <h6 class="mb-2 fw-bold text-lg">Artist</h6>
                            <form>
                              @csrf
                              <select id="filter_artist" name="filter_artist" class="form-select bg-base form-select-sm w-auto radius-8 js-example-basic-singlee">
                                <option>Select Artist</option>
                                  @foreach($get_all_artists as $get_all_artist)
                                    <option value="{{$get_all_artist->id}}">{{$get_all_artist->first_name}}&nbsp;{{$get_all_artist->last_name}}</option>
                                  @endforeach 
                              </select>
                              <button id="filter_artistreset"  class="btn btn-primary-600 btn-sm">Reset</button>
                            </form>
                    </div>


                    
                         <div id="defaultLineChartt" class="apexcharts-tooltip-style-1"></div>
                         <div id="wollaa"></div>
                    
                  </div>
                </div>
              </div>
        </div>

        <div class="row" style="margin-top:20px">
              <div class=" col-xl-12">
                <div class="card h-100 radius-8 border-0 overflow-hidden">
                  <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                      <h6 class="mb-2 fw-bold text-lg">Tracks</h6>
                            <form>
                              @csrf
                              <select id="filter_artist_track" name="filter_artist_track" class="form-select bg-base form-select-sm w-auto radius-8 js-example-basic-singlee">
                                <option>Select Artist</option>
                                  @foreach($get_user_artists as $key=>$get_user_artist)
                                    <option value="{{$get_user_artist}}">{{$get_user_artist}}</option>
                                  @endforeach 
                              </select>
                              <button id="filter_trackreset"  class="btn btn-primary-600 btn-sm">Reset</button>
                            </form>
                    </div>


                    
                         <div id="columnGroupBarChart" class=""></div>
                         <div id="wollaacolumn"></div>
                    
                  </div>
                </div>
              </div>
        </div>
        
        <div class="row" style="margin-top:20px;">

           <!--start firstcolum-->
              <div class="col-lg-12">
                  <div class="card h-100">
                          <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                              <h6 class="text-lg mb-0">Labels</h6>
                              <!-- <select class="form-select bg-base form-select-sm w-auto radius-8">
                                <option>Yearly</option>
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Today</option>
                              </select> -->
                            </div>
                            
                            <div id="charttt1"></div>
                            
                            
                          </div>
                    </div>
              </div>
               <!--end firstcolum-->
        </div>

        <div class="row" style="margin-top:20px;">

           <!--start firstcolum-->
              <div class="col-lg-12">
                  <div class="card h-100">
                          <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                              <h6 class="text-lg mb-0">Albums</h6>
                                  <form>
                                  @csrf
                                  <select id="filter_artist_albums" name="filter_artist_albums" class="form-select bg-base form-select-sm w-auto radius-8 js-example-basic-singlee">
                                    <option>Select Albums</option>
                                      @foreach($get_user_albums as $key=>$get_user_album)
                                        <option value="{{$get_user_album}}">{{$get_user_album}}</option>
                                      @endforeach 
                                  </select>
                                  <button id="filter_albumreset"  class="btn btn-primary-600 btn-sm">Reset</button>
                                </form>
                            </div>
                            
                            <div id="columnGroupBarChart1"></div>
                            <div id="wollaacolumn1"></div>
                            
                          </div>
                    </div>
              </div>
               <!--end firstcolum-->
        </div>
          
       
  </div>

@endsection

@section('script')
<script>
   var options = {
  chart: {
    type: 'bar',
    height: 400
  },
  series: [{
    name: 'user count',
    data: [
            <?php 
                foreach ($name_countvalue as $value) {
                  echo "$value,";
                }
              ?>
          ]
  }],
  dataLabels: {
          enabled: false
      },
  xaxis: {
    title: {
      text: 'Active Users'
    },
    categories: [
            <?php 
                foreach ($first_namevalue as $value) {
                  echo "'$value',";
                }
              ?>
          ]
  }
}

var chart = new ApexCharts(document.querySelector("#charttt"), options);

chart.render();
</script>

<script>
   var options = {
  chart: {
    type: 'bar'
  },
  series: [{
    name: 'Play count',
    data: [
            <?php 
                foreach ($label_data as $value) {
                  echo "$value->PlayCount,";
                }
              ?>
          ]
  }],
  dataLabels: {
          enabled: false
      },
  yaxis: {
       
          labels: {
              formatter: function (value) {
                  return (value / 1000).toFixed(0) + 'k';
              }
          }
  },    
  xaxis: {
    title: {
      text: 'Label'
    },
    categories: [
            <?php 
                foreach ($label_data as $value) {
                  echo "'$value->LabelName',";
                }
              ?>
          ]
  }
}

var chart = new ApexCharts(document.querySelector("#charttt1"), options);

chart.render();
</script>

<script>
var options = { 

      series: [
        <?php 
               foreach ($track_count_val as $valuey) {
                  echo "$valuey,";
               }
             ?>
      ],
      colors: ['#FF9F29', '#487FFF', '#1E3A8A'],
      labels: [
        <?php 
               foreach ($TrackName_val as $value) {
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

    var chart = new ApexCharts(document.querySelector("#tracksOverviewDonutChart"), options);
    chart.render();
  // ================================ Users Overview Donut chart End ================================ 

</script>  

<script>
   var options = {
        series: [{
            name: "Language",
            data: [
                   
                  <?php 
                        foreach ($langCount_val as $value) {
                            echo "'$value',";
                        }
                  ?>
            ]
        }],
        chart: {
            height: 400,
            type: 'line',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            colors: ['#487FFF'],
            width: 4
        },
        markers: {
            size: 0,
            strokeWidth: 3,
            hover: {
                size: 8
            }
        },
        tooltip: {
            enabled: true,
            x: {
                show: true,
            },
            y: {
                show: false,
            },
            z: {
                show: false,
            }
        },
        grid: {
            row: {
                colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            borderColor: '#D1D5DB',
            strokeDashArray: 3,
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: "14px"
                }
            },
        },
        xaxis: {
            title: {
      text: 'Languages'
    },
            categories: [
                   
                  <?php 
                        foreach ($langName_val as $value) {
                            echo "'$value',";
                        }
                  ?>
            ],
            tooltip: {
                enabled: false
            },
            labels: {
                formatter: function (value) {
                    return value;
                },
                style: {
                    fontSize: "14px"
                }
            },
            axisBorder: {
                show: false
            },
            
        }
    };

    var chart = new ApexCharts(document.querySelector("#defaultLineChart"), options);
    chart.render();
</script>


<script>
   // =========================== Sales Statistic Line Chart Start ===============================
   var options = {
    series: [
    {
      name: 'Albums',
      data: [
        <?php 
           foreach ($albumvalue  as $valuee) {
               echo "'$valuee',";
           }
        
        ?>
      ]
    },
    {
      name: 'Tracks',
      data: [
        <?php 
           foreach ($trackvalue  as $valuee) {
               echo "'$valuee',";
           }
        
        ?>
      ]
    }
  ],
    chart: {
      height: 350,
      type: 'bar',
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        top: 6,
        left: 0,
        blur: 4,
        color: "#000",
        opacity: 0.1,
      },
    },
    colors: ["#FF1654", "#247BA0"], // Set color for series
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      colors: ["#FF1654", "#247BA0"], // Specify the line color here
      width: 3
    },
    markers: {
      size: 0,
      strokeWidth: 3,
      hover: {
        size: 8
      }
    },
    tooltip: {
      enabled: true,
      x: {
        show: true,
      },
      y: {
        show: false,
      },
      z: {
        show: false,
      }
    },
    grid: {
      row: {
        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
      borderColor: '#D1D5DB',
      strokeDashArray: 3,
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return value;
        },
        
        style: {
          fontSize: "14px"
        }
      },
    },
    xaxis: {
      categories: [
        <?php 
           foreach ($theyear  as $valuee) {
               echo "'$valuee->year',";
           }
        
        ?>
      ],
      tooltip: {
        enabled: false
      },
      labels: {
        formatter: function (value) {
          return value;
        },
        style: {
          fontSize: "14px"
        }
      },
      axisBorder: {
        show: false
      },
      crosshairs: {
        show: true,
        width: 20,
        stroke: {
          width: 0
        },
        fill: {
          type: 'solid',
          color: '#487FFF40',
          
        }
      }
    }
  };

    var chart = new ApexCharts(document.querySelector("#defaultLineChartt"), options);
    chart.render();
</script>    

<script>
   
   $('#filter_artist').on('change', function (e) {
        e.preventDefault();
        let chart;
        $('#defaultLineChartt').hide();
        $('#wollaa').show();
        var filter_chart__artist_data = $('#filter_artist').val();
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{route("filter_artist")}}',
            method: 'GET',
            data: { date_filter_data: filter_chart__artist_data},
            cache: false,
            async: true,
            success: function (res) {
              if(res.artistdata){
                 console.log(res.artistdata);
                 let fgg = res.artistdata;
                 let artistyear = fgg.map(a => a.year);
                 let artistalbums = fgg.map(a => a.albums);
                 let artistthe_tracks = fgg.map(a => a.tracks);
                 
                 $('#wollaa')
                   .empty()
                   .append(
                      '<div id="newchart11"></div>'
                   )

                  var options = {
                    series: [
                    {
                      name: 'Albums',
                      data: artistalbums
                    },
                    {
                      name: 'Tracks',
                      data: artistthe_tracks
                    }
                  ],
                    chart: {
                      height: 350,
                      type: 'bar',
                      toolbar: {
                        show: false
                      },
                      zoom: {
                        enabled: false
                      },
                      dropShadow: {
                        enabled: true,
                        top: 6,
                        left: 0,
                        blur: 4,
                        color: "#000",
                        opacity: 0.1,
                      },
                    },
                    colors: ["#FF1654", "#247BA0"], // Set color for series
                    // Remove the plotOptions to return to default bar width
                    plotOptions: {
                      bar: {
                        horizontal: false,
                        columnWidth: '15%', // Adjust bar width here
                        endingShape: 'rounded'
                      },
                    },
                    dataLabels: {
                      enabled: false
                    },
                    stroke: {
                      curve: 'smooth',
                      colors: ["#FF1654", "#247BA0"], // Specify the line color here
                      width: 3
                    },
                    markers: {
                      size: 0,
                      strokeWidth: 3,
                      hover: {
                        size: 8
                      }
                    },
                    tooltip: {
                      enabled: true,
                      x: {
                        show: true,
                      },
                      y: {
                        show: false,
                      },
                      z: {
                        show: false,
                      }
                    },
                    grid: {
                      row: {
                        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                      },
                      borderColor: '#D1D5DB',
                      strokeDashArray: 3,
                    },
                    yaxis: {
                      labels: {
                        formatter: function (value) {
                          return value;
                        },
                      
                        style: {
                          fontSize: "14px"
                        }
                      },
                    },
                    xaxis: {
                      categories: artistyear,
                      tooltip: {
                        enabled: false
                      },
                      labels: {
                        formatter: function (value) {
                          return value;
                        },
                        style: {
                          fontSize: "14px"
                        }
                      },
                      axisBorder: {
                        show: false
                      },
                      crosshairs: {
                        show: true,
                        width: 20,
                        stroke: {
                          width: 0
                        },
                        fill: {
                          type: 'solid',
                          color: '#487FFF40',
                          
                        }
                      }
                    }
                  };

                    var chart = new ApexCharts(document.querySelector("#newchart11"), options);
                    chart.render();

              }
            }
        });
   });

</script>

<script>
   var options = {
      series: [{
          name: "Tracks",
          data: [
               <?php 
                    foreach ($theartisttracks  as $valuee) {
                        echo "'$valuee->track_count',";
                    }
                  
                  ?>
          ]
      }],
      chart: {
          type: 'bar',
          height: 464,
          toolbar: {
              show: false
          }
      },
      plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 8,
            columnWidth: 10,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            columnWidth: '23%',
            endingShape: 'rounded',
          }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
          type: 'gradient',
          colors: ['#487FFF'], // Set the starting color (top color) here
          gradient: {
              shade: 'light', // Gradient shading type
              type: 'vertical',  // Gradient direction (vertical)
              shadeIntensity: 0.5, // Intensity of the gradient shading
              gradientToColors: ['#487FFF'], // Bottom gradient color (with transparency)
              inverseColors: false, // Do not invert colors
              opacityFrom: 1, // Starting opacity
              opacityTo: 1,  // Ending opacity
              stops: [0, 100],
          },
      },
      grid: {
          show: true,
          borderColor: '#D1D5DB',
          strokeDashArray: 4, // Use a number for dashed style
          position: 'back',
      },
      xaxis: {
          title: {
      text: 'Artist'
    },
          type: 'category',
          categories: [
                  <?php 
                    foreach ($theartisttracks  as $valuee) {
                        echo "'$valuee->UserName',";
                    }
                  
                  ?>
          ]
      },
      yaxis: {
          labels: {
              // formatter: function (value) {
              //     return (value / 1000).toFixed(0) + 'k';
              // }
          }
      },
      tooltip: {
          y: {
              // formatter: function (value) {
              //     return value / 1000 + 'k';
              // }
          }
      }
    };

    var chart = new ApexCharts(document.querySelector("#columnGroupBarChart"), options);
    chart.render();
</script>

<script>
  $('#filter_artist_track').on('change', function (e) {
        e.preventDefault();
        let chart;
        $('#columnGroupBarChart').hide();
        $('#wollaacolumn').show();
        var filter_chart_artist_track_data = $('#filter_artist_track').val();
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{route("filter_artist_track")}}',
            method: 'GET',
            data: { date_filter_data: filter_chart_artist_track_data},
            cache: false,
            async: true,
            success: function (res) {
              if(res.artist_track_data){
                 console.log(res.artist_track_data);
                 let fgg = res.artist_track_data;
                 let artistTrackName = fgg.map(a => a.TrackName);
                 let ReleaseCount = fgg.map(a => a.ReleaseCount);
                 
                 $('#wollaacolumn')
                   .empty()
                   .append(
                      '<div id="newcharttrack"></div>'
                   )

                  //start of chart

                  var options = {
      series: [{
          name: "Tracks",
          data: ReleaseCount
      }],
      chart: {
          type: 'bar',
          height: 264,
          toolbar: {
              show: false
          }
      },
      plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 8,
            columnWidth: 10,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            columnWidth: '23%',
            endingShape: 'rounded',
          }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
          type: 'gradient',
          colors: ['#487FFF'], // Set the starting color (top color) here
          gradient: {
              shade: 'light', // Gradient shading type
              type: 'vertical',  // Gradient direction (vertical)
              shadeIntensity: 0.5, // Intensity of the gradient shading
              gradientToColors: ['#487FFF'], // Bottom gradient color (with transparency)
              inverseColors: false, // Do not invert colors
              opacityFrom: 1, // Starting opacity
              opacityTo: 1,  // Ending opacity
              stops: [0, 100],
          },
      },
      grid: {
          show: true,
          borderColor: '#D1D5DB',
          strokeDashArray: 4, // Use a number for dashed style
          position: 'back',
      },
      xaxis: {
          title: {
      text: 'Track Name'
    },
          type: 'category',
          categories: artistTrackName
      },
      yaxis: {
          
          title: {
      text: 'Release Count'
    },
          labels: {
              // formatter: function (value) {
              //     return (value / 1000).toFixed(0) + 'k';
              // }
          }
      },
      tooltip: {
          y: {
              // formatter: function (value) {
              //     return value / 1000 + 'k';
              // }
          }
      }
    };

    var chart = new ApexCharts(document.querySelector("#newcharttrack"), options);
    chart.render();
                
                  //end of chart

              }
            }
        });
        
  });
</script>
<script>
    $('#filter_trackreset').on('click', function (e) {
        e.preventDefault();
        location.reload();
    });
</script>

<script>
    $('#filter_artistreset').on('click', function (e) {
        e.preventDefault();
        location.reload();
    });
</script>

<script>
    $('#filter_albumreset').on('click', function (e) {
        e.preventDefault();
        location.reload();
    });
</script>



<script>
   var options = {
      series: [{
          name: "Albums",
          data: [
                    <?php
                      foreach($theartisalbums as $theartisalbum){
                          echo "'$theartisalbum->album_count',";
                      }
                    
                    ?>
          ]
      }],
      chart: {
          type: 'bar',
          height: 500,
          toolbar: {
              show: false
          }
      },
      plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 8,
            columnWidth: 10,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            columnWidth: '23%',
            endingShape: 'rounded',
          }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
          type: 'gradient',
          colors: ['#487FFF'], // Set the starting color (top color) here
          gradient: {
              shade: 'light', // Gradient shading type
              type: 'vertical',  // Gradient direction (vertical)
              shadeIntensity: 0.5, // Intensity of the gradient shading
              gradientToColors: ['#487FFF'], // Bottom gradient color (with transparency)
              inverseColors: false, // Do not invert colors
              opacityFrom: 1, // Starting opacity
              opacityTo: 1,  // Ending opacity
              stops: [0, 100],
          },
      },
      grid: {
          show: true,
          borderColor: '#D1D5DB',
          strokeDashArray: 4, // Use a number for dashed style
          position: 'back',
      },
      xaxis: {
          title: {
      text: 'Label'
          },
          type: 'category',
          categories: [
                     <?php
                      foreach($theartisalbums as $theartisalbum){
                          echo "'$theartisalbum->Labell_Name',";
                      }
                    
                    ?>
          ]
      },
      yaxis: {
          title: {
          text: 'Track'
          },
          labels: {
              // formatter: function (value) {
              //     return (value / 1000).toFixed(0) + 'k';
              // }
          }
      },
      tooltip: {
          y: {
              // formatter: function (value) {
              //     return value / 1000 + 'k';
              // }
          }
      }
    };

    var chart = new ApexCharts(document.querySelector("#columnGroupBarChart1"), options);
    chart.render();
</script>



<script>
   $('#filter_artist_albums').on('change', function (e) {
      e.preventDefault();
      $('#columnGroupBarChart1').hide();
      $('#wollaacolumn1').show();
      
      var filter_artist_albums = $('#filter_artist_albums').val();
        $.ajax({
             headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             url: '{{route("filter_artist_album")}}',
             method: 'GET',
             data: { filter_artist_album_data: filter_artist_albums },
              success: function (res) {
                 let fggcon = res.artist_sound_data;
                 let ArtistLabelName = fggcon.map(a => a.Label_Name);
                 let SoundCount = fggcon.map(a => a.sound_count);
                 console.log(ArtistLabelName);
                $('#wollaacolumn1')
                   .empty()
                   .append(
                      '<div id="newcharttrack11"></div>'
                   )
                
                  var options = {
      series: [{
          name: "Label Count",
          data: SoundCount
      }],
      chart: {
          type: 'bar',
          height: 500,
          toolbar: {
              show: false
          }
      },
      plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 8,
            columnWidth: 10,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            columnWidth: '2%',
            endingShape: 'rounded',
          }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
          type: 'gradient',
          colors: ['#487FFF'], // Set the starting color (top color) here
          gradient: {
              shade: 'light', // Gradient shading type
              type: 'vertical',  // Gradient direction (vertical)
              shadeIntensity: 0.5, // Intensity of the gradient shading
              gradientToColors: ['#487FFF'], // Bottom gradient color (with transparency)
              inverseColors: false, // Do not invert colors
              opacityFrom: 1, // Starting opacity
              opacityTo: 1,  // Ending opacity
              stops: [0, 100],
          },
      },
      grid: {
          show: true,
          borderColor: '#D1D5DB',
          strokeDashArray: 4, // Use a number for dashed style
          position: 'back',
      },
      xaxis: {
          title: {
      text: 'Label Name'
          },
          type: 'category',
          categories: ArtistLabelName
      },
      yaxis: {
          title: {
          text: 'Track'
          },
          labels: {
              // formatter: function (value) {
              //     return (value / 1000).toFixed(0) + 'k';
              // }
          }
      },
      tooltip: {
          y: {
              // formatter: function (value) {
              //     return value / 1000 + 'k';
              // }
          }
      }
    };

    var chart = new ApexCharts(document.querySelector("#newcharttrack11"), options);
    chart.render();

              }
        });
   });
  
</script>

@endsection