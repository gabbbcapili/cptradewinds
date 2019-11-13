<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dist/img/favicon.ico') }}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('PROJECT_NAME', 'QUEDYPROJECT') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- toastr -->
    <link href="{{ asset('plugins/toastr/toastr.min.css')}}" rel="stylesheet">
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js')}} "></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app2.css') }}" rel="stylesheet">
  </head>
  <body>
    <div id="app">
      <div class="example2">
        <div style="height: 40px; background: white;" id="empty-space">
        </div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="padding: 0px;">
          <div class="container-fluid" style="padding: 0px; margin: 0px 47.578px 0px 47.578px">
            <a class="navbar-brand" href="{{ url('/') }}">
              {{--   {{ env('PROJECT_NAME', 'QUEDYPROJECT') }}  --}}
              <img src="{{ asset('images/system/logo.png') }}" class="import-logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <!-- Left Side Of Navbar -->
              <ul class="navbar-nav mr-auto">
              </ul>
              <!-- Right Side Of Navbar -->
              <ul class="navbar-nav ml-auto nav" id="menu-menu" style="">
                <li class="nav-item">
                  <a class="nav-link" href="https://importanything.yuukihost.com/">Home</a>
                </li>
{{--                 <li class="nav-item">
                  <a class="nav-link" href="{{ route('quotationcreate') }}">Get a Quote</a>
                </li> --}}
                <!-- Authentication Links -->
                @guest
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endif
                
                @else
                <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                  </div>
                </li>
                @endguest                  
               <li class="nav-item" style="margin-left: 15px;">
                <a href="/quotation/create" class="nav-link get-quote-btn">GET A QUOTE</a>
              </li>  

               <li class="nav-item" style="">
                <a href="/shipment/create" class="nav-link get-quote-btn">START A SHIPMENT</a>
              </li>  

              </ul>
            </div>
          </div>
        </nav>
      </div>
      <main class="" id="overlay">
        @yield('content')
      </main>
  <!-- /.content-wrapper -->
  <footer class="main-footer no-print import-footer">
    <div class="hidden-xs" style="margin-top: 10px; float: right;">
      Copyright 2019. All Rights Reserved. Powered by Quedy Media
    </div>
    <img src="{{ asset('images/system/logo.png') }}" style="height: 45px;">
  </footer>

    </div>
    @yield('javascript')
  </body>
</html>