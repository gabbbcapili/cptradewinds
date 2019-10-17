@inject('request', 'Illuminate\Http\Request')
@extends('layouts.base')
@section('title', $title)

@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header no-print">
      <h1>
        {{ $title }}
        <small>manage your {{ $title }}</small>
      </h1>
    </section>
    
    <!-- Main content -->
    <section class="content no-print">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            <h3 class="box-title">{{ $title }} List</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
              <div class="table-responsive">
              <table id="order_list" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">Shipment ID</th>
                  <th class="text-center">Date</th>
                  @if($request->user()->isAdmin())
                    <th class="text-center">Source</th>
                  @endif
                  <!-- <th class="text-center">Logs</th> -->
                  @if(request()->segment(1) == 'quotation')
                  <th class="text-center">Price</th>
                  @endif
                  <th class="text-center">Status</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                <tr>
                  <td>{{ $order->shipment_id }}</td>
                  <td> {{ date('d-m-Y', strtotime($order->created_at )) }}</td> 
                  @if($request->user()->isAdmin())
                    <td>{{ $order->source ? $order->source->name : ''}}</td>
                  @endif
                  @if(request()->segment(1) == 'quotation')
                    <td>{{ number_format($order->price, 2) }}</td>
                  @endif

              <!--     <td class="text-center"><a href="#" class="view_order btn" data-href="{{ action('OrderController@show', [$order->id] ) }}"><i class="fa fa-file-text"></i> View Logs</a></td> -->
                  <td align="center"><span class="label bg-orange">{{ $order->get_status_display() }}</span></td>
                  <td align="center">
                            <!-- <a href="#" class="view_order btn" data-href="{{ action('OrderController@show', [$order->id] ) }}"><i class="fa fa-eye"></i> View</a> -->
                            <a href="{{ action('OrderController@show', [$order->id] ) }}" class="btn"><i class="fa fa-eye"></i> View</a>
                            
                            @if(($order->status >= 3 && $order->status <= 7) && auth()->user()->isSupplier() && $order->withQuote == false)
                            <a href="{{ action('OrderController@edit', $order->id) }}" class="btn"><i class="glyphicon glyphicon-edit"></i>
                             Continue/Cancel Shipment
                           </a>
                            @endif
                            @if($order->status == 2 && auth()->user()->isCustomer())
                              <a href="{{ action('OrderController@edit', $order->id) }}" class="btn"><i class="glyphicon glyphicon-edit"></i>
                                 Edit Details
                               </a>
                            @endif

                            @if(($order->status == 1) && (auth()->user()->isCustomer()))
                            <a href="#" class="confirmation btn" data-title="Are you sure to cancel this transaction?" data-text="Once cancelled, you will not be able undo this action!"  data-href="{{ action('OrderController@cancel', $order->id) }}"><i class="fa fa-ban"></i> Cancel</a>
                            @endif

                            @if($order->status == 4 && auth()->user()->isAdmin())
                              <a href="#" class="confirmation btn" data-title="Are you sure to cancel this transaction?" data-text="Once cancelled, you will not be able undo this action!"  data-href="{{ action('OrderController@cancel', $order->id) }}"><i class="fa fa-ban"></i> Cancel/Bogus</a>
                            @endif

                  <!--           @if(($order->status == 3) && (auth()->user()->isSupplier()))
                            <a href="#" class="confirmation btn" data-title="Are you sure to cancel this transaction?" data-text="Once cancelled, you will not be able undo this action!"  data-href="{{ action('OrderController@cancel', $order->id) }}"><i class="fa fa-ban"></i> Cancel Shipment</a>
                            @endif -->
                     <!--        @if($order->status == 3 && auth()->user()->isSupplier())
                            <a href="#" class="confirmation btn" data-title="Are you sure to request quotation for this transaction" data-text="If yes, you will not be able undo this action!"  data-href="{{ action('OrderController@forQuotation', $order->id) }}"><i class="fa fa-cart-arrow-down"></i> Request Shipment Quotation</a>
                            @endif -->
                            @if(($order->status == 4 || $order->status == 3) && auth()->user()->isAdmin())
                            <a class="btn" href="{{ action('OrderController@addQuotation', $order->id) }}"><i class="fa fa-plus"></i> Add Shipment Quotation</a>
                            @endif
                            @if(($order->status == 5 || $order->status == 4) && $order->price != null)
                            <a href="#" class="btn view_quotation" data-href="{{ action('OrderController@viewQuotation', $order->id) }}"><i class="fa fa-eye"></i> View Quotation</a>
                            @endif
                            @if($order->status == 5 && auth()->user()->isCustomer())
                            <a href="#" class="btn confirmation" data-title="Are you sure to accept fee for this transaction?" data-text="Once accepted, you will not be able undo this action!" data-href="{{ action('OrderController@acceptFee', $order->id) }}"><i class="fa fa-check-square-o"></i>Confirm fee</a>
                            @endif
                         <!--    @if($order->status == 6 && auth()->user()->isSupplier() && $order->warehouse == null)
                            <a href="#" class="btn choose_warehouse" data-href="{{ action('OrderController@chooseWarehouse', $order->id) }}"><i class="fa fa-edit"></i> Choose Warehouse</a>
                            @endif
                            @if($order->status == 6 && auth()->user()->isSupplier() && $order->warehouse != null)
                            <a href="#" class="btn view_mark" data-href="{{ action('OrderController@showMark', $order->id) }}"><i class="fa fa-eye"></i> View Shipping Mark</a>
                            @endif
                            @if($order->status == 6 && auth()->user()->isSupplier()  && $order->warehouse != null)
                            <a class="btn" href="{{ action('OrderController@GetStorePhotos', $order->id) }}"><i class="fa fa-plus"></i> Add Photos</a>
                            @endif -->
                        <!--     @if($order->status == 7 && auth()->user()->isAdmin())
                            <a href="#" class="btn confirmation" data-title="Are you sure to proceed this transaction?" data-text="Please do double check the proforma invoice." data-href="{{ action('OrderController@acceptInvoice', $order->id) }}"><i class="fa fa-check-square-o"></i> Confirm Proforma Invoice</a>
                            @endif
                            @if(($order->status == 7) && (auth()->user()->isAdmin()))
                            <a href="#" class="confirmation btn" data-title="Are you sure to decline this transaction?" data-text="Once declined, you will not be able undo this action!"  data-href="{{ action('OrderController@cancel', $order->id) }}"><i class="fa fa-ban"></i> Decline Order</a>
                            @endif -->

                    <!--         @if($order->status == 7 && auth()->user()->isSupplier())
                            <a href="#" class="btn choose_warehouse" data-href="{{ action('OrderController@getProofOfShipment', $order->id) }}"><i class="fa fa-plus"></i> Upload proof of shipment</a>
                            @endif -->
                            @if($order->status == 8 && auth()->user()->isAdmin())
                            <a href="#" class="btn choose_warehouse" data-href="{{ action('OrderController@editDue', $order->id) }}"><i class="fa fa-check"></i> Request for Payment</a>
                            @endif
                            @if($order->status == 9 && auth()->user()->isAdmin())
                            <a href="#" class="btn confirmation" data-title="Are you sure to proceed this transaction?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('OrderController@OnGoing', $order->id) }}"><i class="fa fa-check-square-o"></i> On-Going?</a>
                            @endif
                            @if($order->status == 10 && auth()->user()->isAdmin())
                            <a href="#" class="btn confirmation" data-title="Are you sure to proceed this transaction?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('OrderController@phArrived', $order->id) }}"><i class="fa fa-check-square-o"></i> Arrived (PH)</a>
                            @endif
                             @if($order->status == 11 && auth()->user()->isCustomer())
                           <a href="{{ action('OrderController@edit', $order->id) }}" class="btn"><i class="glyphicon glyphicon-edit"></i>
                             Add Payment
                           </a>
                            @endif
                            @if($order->status == 12 && auth()->user()->isAdmin())
                            <a href="#" class="btn confirmation" data-title="Are you sure to approve this transaction payment / Request for pickup?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('OrderController@approvePayment', $order->id) }}"><i class="fa fa-check-square-o"></i> Request for Pickup</a>
                            @endif
                            @if($order->status == 12 && auth()->user()->isAdmin())
                            <a href="#" class="btn confirmation" data-title="Are you sure to decline this transaction payment?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('OrderController@declinePayment', $order->id) }}"><i class="fa fa-remove"></i> Decline Payment</a>
                            @endif
                            @if($order->status == 13 && auth()->user()->isAdmin())
                            <a href="#" class="btn confirmation" data-title="Are you sure to complete this transaction?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('OrderController@completeTransaction', $order->id) }}"><i class="fa fa-check-square-o"></i> Delivered / Picked up?</a>
                            @endif
                            @if(($order->status >=1 && $order->status < 15 ) && (auth()->user()->isAdmin()))
                            <a href="#" class="confirmation btn red" data-title="Are you sure to delete this transaction?" data-text="Once deleted, you will not be able undo this action!"  data-href="{{ action('OrderController@delete', $order->id) }}"><i class="fa fa-ban"></i> Delete</a>
                            @endif
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->


@endsection

@section('javascript')

<script type="text/javascript">
$(document).ready( function(){
  $(document).on("click", '.view_order', function(){
        var url = $(this).data("href");
        var container = $(".view_modal");
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html",
            success: function(result){
                $(container).html(result).modal("show");
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
          }
        });
    });

  $(document).on("click", '.view_mark', function(){
        var url = $(this).data("href");
        var container = $(".view_modal");
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html",
            success: function(result){
                $(container).html(result).modal("show");
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
          }
        });
    });
   $(document).on("click", '.view_quotation', function(){
        var url = $(this).data("href");
        var container = $(".view_modal");
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html",
            success: function(result){
                $(container).html(result).modal("show");
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
          }
        });
    });

  $(document).on("click", '.choose_warehouse', function(){
        var url = $(this).data("href");
        var container = $(".view_modal");
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html",
            success: function(result){
                $(container).html(result).modal("show");
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
          }
        });
    });

    $('#order_list').DataTable({
        aaSorting: [[0, 'desc']],
        columnDefs: [ {
        "targets": [3,4],
        "orderable": false,
        "searchable": false
    }],
    });
    
    $('.confirmation').click(function(){
      swal({
          title: $(this).data('title'),
          text:  $(this).data('text'),
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "GET",
            url: $(this).data('href'),
            dataType: "json",
            success: function(data){
              console.log(data.error);
              if (data.error){
                toastr.error(data.error);
              }
              if (data.success){
                location.reload();
              }
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
            toastr.error('Sorry, Something went wrong. Please try again later.');
          }
          });
        }
      });
    });  

      $('.pickWarehouse').click(function(){
      var warehouse = {
      'Tondo': 'Tondo1',
      'Cubao': 'Cubao1',
    }
    console.log
    });

});  
</script>
@endsection
