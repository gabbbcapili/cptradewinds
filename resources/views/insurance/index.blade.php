@extends('layouts.base')
@section('title', 'Insurance')

@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header no-print">
      <h1>
        Insurance
        <small>manage your insurance</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Insurance List</h3>
              @if(auth()->user()->isCustomer())
              <div class="box-tools">
                <a href="{{ action('InsuranceController@create') }} " class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Add Insurance Service</a>
              </div>
              @endif
            </div>
            <!-- /.box-header -->

            <div class="box-body">
              <div class="table-responsive">
              <table id="payment_table" class="table table-bordered table-striped">
                <thead>
                <tr class="text-center">
                  <th>Transaction#</th>
                  <th>Order</th>
                  <th>Total Amount</th>
                  <th>Fee</th>
                  <th>Attachments</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($insurances as $insurance)
                  <tr>
                    <td>{{ $insurance->id }}</td>
                    <td> <a href="#" class="modal_button btn" data-href="{{ action('OrderController@show', [$insurance->order_id] ) }}">View Order #{{ $insurance->order_id }}</a> </td>
                    <td>{{ number_format($insurance->amount, 2) }}</td>
                    <td>{{ number_format($insurance->fee, 2) }}</td>
                    <td>
                      <a href="#" class="modal_button btn" data-href="{{ action('InsuranceController@showattachments', [$insurance->id] ) }}"><i class="fa fa-eye"></i> View Attachments</a>

                     
                   </td>
                    <td><span class="label bg-orange">{{ $insurance->getStatusDisplay() }}</span></td>
                    <td>
                      @if($insurance->status == 1 && auth()->user()->isAdmin())
                        <a href="#" class="btn confirmation" data-title="Are you sure to approve this insurance transaction?" data-text="Please double check the proforma invoice and application declartion first." data-href="{{ action('InsuranceController@approve', $insurance->id) }}"><i class="fa fa-check-square-o"></i> Approve Insurance</a>
                        @endif
                        @if($insurance->status == 1 && auth()->user()->isAdmin())
                        <a href="#" class="btn confirmation" data-title="Are you sure to reject this insurance transaction?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('InsuranceController@reject', $insurance->id) }}"><i class="fa fa-times"></i> Reject Insurance</a>
                        @endif

                        @if(($insurance->status == 3 || $insurance->status == 4) && auth()->user()->isCustomer())
                        <a href="#" class="btn modal_button" data-href="{{ action('InsuranceController@getDeposit', $insurance->id) }}"><i class="fa fa-edit"></i> {{ $insurance->deposit ? 'Update' : 'Add' }} Deposit Slip</a>
                        @endif
                        @if($insurance->status == 4 && auth()->user()->isAdmin())
                        <a href="#" class="btn confirmation" data-title="Are you sure to complete this transaction?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('InsuranceController@completeTransaction', $insurance->id) }}"><i class="fa fa-check-square-o"></i> Approve Payment</a>
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
    $('#payment_table').DataTable({
        aaSorting: [[0, 'desc']],
        columnDefs: [ {
        "targets": [4,5],
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
              if (data.status){
                toastr.error(data.status);
              }
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
