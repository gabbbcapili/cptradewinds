@inject('request', 'Illuminate\Http\Request')
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ env('PROJECT_NAME', 'QUEDYPROJECT') }} - @yield('title')</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dist/img/favicon.ico') }}" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" >
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
  <!-- toastr -->
  <link href="{{ asset('plugins/toastr/toastr.min.css')}}" rel="stylesheet">
    <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- custom -->
  <link rel="stylesheet" href="{{ asset('app/app.css') }}" >
  <link rel="stylesheet" href="{{ asset('css/base2.css') }}" >
    <!-- Pace style -->
  <link rel="stylesheet" href="../../plugins/pace/pace.min.css">
  <!-- select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/select2.css') }}">
@yield('css')
</head>
<body class="hold-transition skin-red sidebar-mini">
<div class="wrapper">
      <div style="height: 36px; background: white;">
    </div>
<div class="example2">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="https://importanything.yuukihost.com/"><img src="{{ asset('images/system/logo.png') }}">
        </a>
      </div>
      <div id="navbar2" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          @include('layouts.partials.top-nav')
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
    <!--/.container-fluid -->
  </nav>
</div>
  <header class="main-header">

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" style="margin-left: 0px;">
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            @if (!request()->user())
             <a href="{{ route('login') }}">Login</a>
            @endif
            @if (request()->user())
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              
              <span class="">
                 <img src="{{ asset('dist/img/user512x512.png') }} " class="user-image" alt="User Image">
                {{ request()->user()->name }}  {{ request()->user()->last_name }}
                

              </span>
            </a>
            
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{ asset('dist/img/user512x512.png') }} " class="img-circle" alt="User Image">

                <p>
                  @if (request()->user())
                  {{ request()->user()->name }}  {{ request()->user()->last_name }}
                  @endif
                  <!-- <small>Member since Nov. 2012</small> -->
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat modal_button" data-href="{{ action('UserController@changePasswordForm')}}"> <i class="fa fa-key"></i>Change Password</a>
                </div>
                <div class="pull-right">
                      <a class="btn btn-default btn-flat" href="{{ route('logout') }}"onclick="event.preventDefault(); 
                      document.getElementById('logout-form').submit();">Sign Out</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                      </form>
                </div>
              </li>
            </ul>
            @endif
          </li>

        </ul>
      </div>
    </nav>
  </header>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin: 0px !important;">
      <div class="container">
        @yield('content')
      </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer no-print import-footer">
    <div class="pull-right hidden-xs" style="margin-top: 10px;">
      Copyright 2019. All Rights Reserved. Powered by Quedy Media
    </div>
    <img src="{{ asset('images/system/logo.png') }}" style="height: 45px;">
  </footer>


  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- Toastr -->
 <script src="{{ asset('plugins/toastr/toastr.min.js')}} "></script>
<!-- PACE -->
<script src="{{ asset('bower_components/PACE/pace.min.js') }}"></script>
<!-- MASK -->
<script src="{{ asset('bower_components/mask/mask.js') }}"></script>
<!-- select 2 -->
<script src="{{ asset('plugins/select2/select2.js') }}"></script>
<!-- PrintThis -->
<script src="{{ asset('plugins/printThis/printThis.js') }}"></script>
<!-- Swal -->
<script src="{{ asset('plugins/swal/swal.js') }}"></script>
<!-- popper -->
<script src="{{ asset('plugins/popper/popper.min.js') }}"></script>
 <!-- Custom js -->
<script src="{{ asset('app/app.js')}} "></script>
<!-- Datatable -->
<script type="text/javascript">
  $( document ).ready(function() {
    $('.datatable').DataTable();
});
</script>

<script type="text/javascript">
      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ajaxStart(function() { Pace.restart(); });
         $('.select2').select2(); 
    });
</script>


  @yield('javascript')
<script>
$(document).ready(function() {
  @if(Session::has('status'))
 toastr.{{ Session::get('alert-class', 'success') }}('{{ Session::get('status') }}', '', {timeOut: 10000})
  @endif
  @if(Session::has('success'))
 toastr.{{ Session::get('alert-class', 'success') }}('{{ Session::get('success') }}', '', {timeOut: 10000})
  @endif
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
});


$(document).on("click", '.modal_button', function(){
        var url = $(this).data("href");
        var container = $(".view_modal");
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html",
            success: function(result){
              if (result.status){
                toastr.error(result.status);
              }else{
                $(container).html(result).modal("show");
              }
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
          }
        });
    });
</script>

<script type="text/javascript">
$( "a" ).each(function( index ) {
  var href = $(this).attr('href');
  var value = href.replace( "/index.php/", "/" );
  $(this).attr('href', value);
});

$( "img" ).each(function( index ) {
  var src = $(this).attr('src');
  var value = src.replace( "/index.php/", "/" );
  $(this).attr('src', value);
});

</script>



@yield('javascript2')



<div class="modal fade view_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel"></div>

</body>
</html>
