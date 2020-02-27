 @if (request()->user())
        @if(request()->user()->isCustomer() || request()->user()->isSupplier())
        <li class="nav-item {{ $request->segment(1) == 'profile' ? 'active' : '' }}">
          <a href="{{ action('HomeController@profile') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        @endif

        @if(request()->user()->isAdmin() || request()->user()->isCustomer() || request()->user()->isSupplier())
        <li class="nav-item dropdown {{ $request->segment(1) == 'orders' ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-truck"></i> <span>Shipments</span>  <span class="badge badge-primary" id="badge_shipments"></span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-down pull-right"></i>
                  </span></a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ action('OrderController@index') }}"><i class="fa fa-list"></i> List Shipments </a>
            @if (auth()->user()->isCustomer() || auth()->user()->isSupplier())
            <a class="dropdown-item" href="{{ action('OrderController@create') }}"><i class="fa fa-plus-circle"></i> Add Shipment</a>
            @endif
          </div>
        </li>

        <li class="nav-item dropdown {{ $request->segment(1) == 'quotation' ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-file-text"></i>  <span>Quotations</span>  <span class="badge badge-primary" id="badge_quotations"></span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-down pull-right"></i>
                  </span></a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('quotationlist') }}"><i class="fa fa-list"></i> List Quotation </a>
            @if (auth()->user()->isCustomer() || auth()->user()->isSupplier())
            <a class="dropdown-item" href="{{ route('quotationcreate') }}"><i class="fa fa-plus-circle"></i> Add Quotation</a>
            @endif
          </div>
        </li>
        @endif 

         @if(auth()->user()->isCustomer() ||  auth()->user()->isAdmin())
        <li class="nav-item dropdown {{ $request->segment(1) == 'payments' ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-money"></i>  <span>Payment Services</span><span class="badge badge-primary" id="badge_payments"></span>  
            <span class="pull-right-container">
                    <i class="fa fa-angle-down pull-right"></i>
                  </span></a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ action('PaymentController@index') }}"><i class="fa fa-list"></i> List Payments </a>
            @if(auth()->user()->isCustomer())
            <a class="dropdown-item" href="{{ action('PaymentController@create') }}"><i class="fa fa-plus-circle"></i> Add Payment Service</a>
            @endif
          </div>
        </li>
        @endif

        @if(auth()->user()->isCustomer() || auth()->user()->isAdmin())
        <li class="nav-item dropdown {{ $request->segment(1) == 'insurance' ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-shield"></i>  <span>Insurance Services</span>  
            <span class="pull-right-container">
                    <i class="fa fa-angle-down pull-right"></i>
                  </span></a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ action('InsuranceController@index') }}"><i class="fa fa-list"></i> List Insurance </a>
            @if(auth()->user()->isCustomer())
            <a class="dropdown-item" href="{{ action('InsuranceController@create') }}"><i class="fa fa-plus-circle"></i> Add Insurance Service</a>
            @endif
          </div>
        </li>
        @endif


       @if(auth()->user()->isCustomer() || auth()->user()->isAdmin() || auth()->user()->isAdmin3())
        <li class="nav-item dropdown {{ $request->segment(1) == 'clearance' ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-handshake-o"></i> <span>Customs Clearance</span> 
            <span class="pull-right-container">
                    <i class="fa fa-angle-down pull-right"></i>
                  </span></a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ action('ClearanceController@index') }}"><i class="fa fa-list"></i> List Customs Clearance </a>
            @if(auth()->user()->isCustomer())
            <a class="dropdown-item" href="{{ action('ClearanceController@create') }}"><i class="fa fa-plus-circle"></i> Add Customs Clearance</a>
            @endif
          </div>
        </li>
        @endif

       @if(auth()->user()->isAdmin())
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
             <i class="fa fa-gear"></i> <span>Settings</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-down pull-right"></i>
                  </span></a>
          <div class="dropdown-menu">
            <a href="#" class="dropdown-item modal_button" data-href="{{ action('SettingController@index') }}"><i class="fa fa-dollar"></i> Dollar Rate</a>

            <a href="{{ action('SourceController@index') }}" class="dropdown-item"><i class="fa fa-crosshairs"></i> Sources</a>
          </div>
        </li>
        @endif


        @if(auth()->user()->isAdmin())
        <li class="nav-item dropdown {{ $request->segment(1) == 'user' ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user-circle" aria-hidden="true"></i> <span>Users</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-down pull-right"></i>
                  </span></a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ action('UserController@buyers') }}"><i class="fa fa-list"></i> List of Buyers </a>
            <a class="dropdown-item" href="{{ action('UserController@suppliers') }}"><i class="fa fa-list"></i> List of Suppliers </a>
          </div>
        </li>
        @endif
@endif