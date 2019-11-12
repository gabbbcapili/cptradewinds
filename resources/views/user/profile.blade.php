@extends('layouts.base')
@section('title', 'Dashboard')
@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>manage your dashboard</small>
      </h1>

    </section>
    <section class="content">

	<div class="box box-solid">
		<div class="box-body">
      <h4>Name: {{ request()->user()->name }} {{ request()->user()->last_name }}  <a href="#" class="modal_button" data-href="{{ action('UserController@edit') }}"><i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit Profile"></i></a> </h4>
			<h4>
			Client Id: {{ request()->user()->id }}
			</h4>
			<br>
			
      @if(request()->user()->isCustomer())
        Please send this link to your supplier:<br>

        <br>For Quotation: <u style="color:blue" id="quotationLink" onclick="copyToClipboard('quotationLink')">{{ route('quotationcreate', ['clientid='.request()->user()->id]) }}</u>
        &nbsp  <button class="btn btn-sm" onclick="copyToClipboard('quotationLink')" data-toggle="tooltip" data-placement="top" title="Copy to Clipboard"><i class="fa fa-copy"></i>Copy</button>
        <br>
        <br>For Shipment: <u style="color:blue" id="shipmentLink" onclick="copyToClipboard('shipmentLink')">{{ route('shipmentcreate', ['clientid='.request()->user()->id]) }}</u>
        &nbsp  <button class="btn btn-sm" onclick="copyToClipboard('shipmentLink')" data-toggle="tooltip" data-placement="top" title="Copy to Clipboard"><i class="fa fa-copy"></i>Copy</button>
      @endif




      
   <!--    <br>
      or -->
      <br>

  <h2>   Submit a Quote Request  <a href="{{ route('quotationcreate') }}"><u  data-toggle="tooltip" data-placement="top" title="You must have the gross weight and dimensions of your shipment to fill up a quote request">now!</u></a></h2> <br><br>
     <div class="row">
      

      <!-- quote request -->
       <div class="col-md-12">

        <div class="box-header">
              <h3 class="box-title">Quotation List</h3>
            </div>
         <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">Quotation ID</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Price</th>
                  <th class="text-center">Status</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($withQuote as $order)
                <tr>
                  <td><a href="{{ action('OrderController@show', [$order->id] ) }}">{{ $order->id }}</a></td>
                  <td>{{ $order->created_at }}</td>
                  <td> {{ $order->price }}</td>
                  <td><span class="label bg-orange">{{ $order->get_status_display() }}</span></td>
                </tr>
                  @endforeach
                </tbody>
              </table>
              </div>
         
       </div>
       <!-- Shipments -->
       <div class="col-md-12">
        <div class="box-header">
              <h3 class="box-title">Shipment List</h3>
            </div>
         <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                <tr>
                  
                  <th class="text-center">Shipment ID</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">View</th>
                  <th class="text-center">Status</th>
                </tr>
                </thead>
                <tbody>
                   @foreach($shipments as $order)
                <tr>
                  <td><a href="{{ action('OrderController@show', [$order->id] ) }}">{{ $order->id }}</a></td>
                  <td>{{ $order->created_at }}</td>
                  <td class="text-center"><a target="_blank" href="{{ action('OrderController@show', [$order->id] ) }} " class="btn"><i class="fa fa-eye"></i>View</a></td>
                  <td><span class="label bg-orange">{{ $order->get_status_display() }}</span></td>
                </tr>
                  @endforeach
                </tbody>
              </table>
              </div>
       </div>

     </div>  <!-- row -->

			<div style="height:100px"></div>
		</div>
	</div> <!--box end-->

    </section>
@endsection
@section('javascript')



<script type="text/javascript">

function copyToClipboard(elementId) {

  // Create a "hidden" input
  var aux = document.createElement("input");

  // Assign it the value of the specified element
  aux.setAttribute("value", document.getElementById(elementId).innerHTML);

  // Append it to the body
  document.body.appendChild(aux);

  // Highlight its content
  aux.select();

  // Copy the highlighted text
  document.execCommand("copy");

  // Remove it from the body
  document.body.removeChild(aux); 
  toastr.success('Link copied to clipboard.');
}
</script>

<script type="text/javascript">
  $(document).ready( function(){ 
    $('.table').DataTable({
        aaSorting: [[0, 'desc']],
    });
  });
</script>

@endsection

