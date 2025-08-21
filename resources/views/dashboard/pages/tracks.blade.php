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
  <h6 class="fw-semibold mb-0">Tracks</h6>
  
</div>

   

    <div class="row gy-4" id="trackspagee">
        @include('dashboard.pages.trackspage')
    </div>
    <!-- Data Loader -->

    <div class="auto-loadtrack text-center" style="display: none;">

        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"

            x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">

            <path fill="#000"

                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">

                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"

                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />

            </path>

        </svg>

    </div>

@endsection

@section('script')

<script>

    var ENDPOINT = "{{ route('allTracks') }}";
    var page = 1;
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= ($(document).height() - 20)) {
            page++;
            infinteLoadMore(page);
        }
    });

    function infinteLoadMore(page) {

        $.ajax({
                url: ENDPOINT + "?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-loadtrack').show();
                }

            })
            .done(function (response) {
                if (response.htmltracks == '') {
                    $('.auto-loadtrack').html("We don't have any data to display :(");
                    return;
                }
                $('.auto-loadtrack').hide();
                $("#trackspagee").append(response.htmltracks);

            })

            .fail(function (jqXHR, ajaxOptions, thrownError) {

                console.log('Server error occured');

            });

    }

</script>
  
@endsection

