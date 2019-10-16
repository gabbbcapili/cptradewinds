 @if (request()->user())
 <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        @if(request()->user()->isCustomer() || request()->user()->isSupplier())
        <li class="{{ $request->segment(1) == 'profile' ? 'active' : '' }}">
          <a href="{{ action('HomeController@profile') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        @endif
        @if(request()->user()->isAdmin() || request()->user()->isCustomer() || request()->user()->isSupplier())
        <li class="treeview {{ $request->segment(1) == 'orders' ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-truck"></i> <span>Shipments</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'orders' && $request->segment(2) == '' ? 'active' : '' }}">
              <a href="{{ action('OrderController@index') }}"><i class="fa fa-list"></i> List Shipments </a>
            </li>
            @if (auth()->user()->isCustomer() || auth()->user()->isSupplier())
            <li class="{{ $request->segment(1) == 'orders' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{ action('OrderController@create') }}"><i class="fa fa-plus-circle"></i> Add Shipment</a></li>
            @endif
          </ul>
        </li>

        <li class="treeview {{ $request->segment(1) == 'quotation' ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-file-text"></i> <span>Quotations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'quotation' && $request->segment(2) == '' ? 'active' : '' }}">
              <a href="{{ route('quotationlist') }}"><i class="fa fa-list"></i> List Quotation </a>
            </li>
            @if (auth()->user()->isCustomer() || auth()->user()->isSupplier())
            <li class="{{ $request->segment(1) == 'quotation' && $request->segment(2) == 'create' ? 'active' : '' }}"><a href="{{ route('quotationcreate') }}"><i class="fa fa-plus-circle"></i> Add Quotation</a></li>
            @endif
          </ul>
        </li>

        @endif 
        @if(auth()->user()->isAdmin())
    <!--     <li class="treeview {{ $request->segment(1) == 'admin' ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-shopping-cart"></i> <span>Orders by Status</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'admin' && $request->segment(2) == 'quotation' ? 'active' : '' }}">
              <a href="{{ action('AdminOrderController@Quotation') }}"><i class="fa fa-list"></i> List Quotations </a>
            </li>
            <li class="{{ $request->segment(1) == 'admin' && $request->segment(2) == 'pending' ? 'active' : '' }}">
              <a href="{{ action('AdminOrderController@Pending') }}"><i class="fa fa-list"></i> List Pending </a>
            </li>
            <li class="{{ $request->segment(1) == 'admin' && $request->segment(2) == 'transit' ? 'active' : '' }}">
              <a href="{{ action('AdminOrderController@Transit') }}"><i class="fa fa-list"></i> List In Transit </a>
            </li>
            <li class="{{ $request->segment(1) == 'admin' && $request->segment(2) == 'completed' ? 'active' : '' }}">
              <a href="{{ action('AdminOrderController@Completed') }}"><i class="fa fa-list"></i> List Completed </a>
            </li>
          </ul>  
        </li>  -->
        @endif

          @if(auth()->user()->isCustomer() ||  auth()->user()->isAdmin())
        <li class="treeview {{ $request->segment(1) == 'payments' ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-money"></i> <span>Payment Services</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'payments' && $request->segment(2) == '' ? 'active' : '' }}">
              <a href="{{ action('PaymentController@index') }}"><i class="fa fa-list"></i> List Payments </a>
            </li>
             @if(auth()->user()->isCustomer())
            <li class="{{ $request->segment(1) == 'payments' && $request->segment(2) == 'create' ? 'active' : '' }}">
              <a href="{{ action('PaymentController@create') }}"><i class="fa fa-plus-circle"></i> Add Payment Service </a>
            </li>
            @endif
         
          </ul>  
        </li>  
           @endif


        @if(auth()->user()->isCustomer() || auth()->user()->isAdmin())
        <li class="treeview {{ $request->segment(1) == 'insurance' ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-shield"></i> <span>Insurance Services</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'insurance' && $request->segment(2) == '' ? 'active' : '' }}">
              <a href="{{ action('InsuranceController@index') }}"><i class="fa fa-list"></i> List Insurance </a>
            </li>
            @if(auth()->user()->isCustomer())
            <li class="{{ $request->segment(1) == 'insurance' && $request->segment(2) == 'create' ? 'active' : '' }}">
              <a href="{{ action('InsuranceController@create') }}"><i class="fa fa-plus-circle"></i> Add Insurance Service </a>
            </li>
            @endif
          </ul>  
        </li>  
        @endif

@if(auth()->user()->isCustomer() || auth()->user()->isAdmin() || auth()->user()->isAdmin3())
        <li class="treeview {{ $request->segment(1) == 'clearance' ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-handshake-o"></i> <span>Customs Clearance</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'clearance' && $request->segment(2) == '' ? 'active' : '' }}">
              <a href="{{ action('ClearanceController@index') }}"><i class="fa fa-list"></i> List Customs Clearance </a>
            </li>
            @if(auth()->user()->isCustomer())
            <li class="{{ $request->segment(1) == 'clearance' && $request->segment(2) == 'create' ? 'active' : '' }}">
              <a href="{{ action('ClearanceController@create') }}"><i class="fa fa-plus-circle"></i> Add Customs Clearance </a>
            </li>
            @endif
          </ul>  
        </li>  
        @endif
        
@if(auth()->user()->isAdmin())
        <li class="treeview">
          <a href="#">
            <i class="fa fa-gear"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="#" data-href="{{ action('SettingController@index') }}" class="modal_button"><i class="fa fa-dollar"></i> Dollar Rate </a>
            </li>
          </ul>  
        </li>  
   @endif

        @if(auth()->user()->isAdmin())
        <li class="treeview {{ $request->segment(1) == 'user' ? 'active' : '' }}">
          <a href="#">
          <i class="fa fa-user-circle" aria-hidden="true"></i> <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $request->segment(1) == 'user' && $request->segment(2) == 'buyers' ? 'active' : '' }}">
              <a href="{{ action('UserController@buyers') }}"><i class="fa fa-list"></i> List of Buyers </a>
             
            <li class="{{ $request->segment(1) == 'user' && $request->segment(2) == 'suppliers' ? 'active' : '' }}">
              <a href="{{ action('UserController@suppliers') }}"><i class="fa fa-list"></i> List of Suppliers </a>
            </li>
            </li>
           
        @endif


      </ul>
@endif
