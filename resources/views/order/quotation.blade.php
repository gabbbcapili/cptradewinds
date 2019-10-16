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
                  <th class="text-center">Transaction#</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Quotation Price</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                <tr>
                  <td>{{ $order->shipment_id }}</td>
                  <td> {{ date('d-m-Y', strtotime($order->created_at )) }}</td>
                  <td class="text-center">{{ $order->price ? $order->price : '--' }}</td>
                  <td><span class="label bg-orange">{{ $order->get_status_display() }}</span></td>
                  <td align="center">
                            <a href="{{ action('OrderController@show', [$order->id] ) }}" class="btn"><i class="fa fa-eye"></i> View</a>
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



});  
</script>

@endsection
