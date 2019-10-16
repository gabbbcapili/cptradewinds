@extends('layouts.base')
@section('title', 'Payment')

@section('content')
   <!-- Content Header (Page header) -->
    <section class="content-header no-print">
      <h1>
        Payments
        <small>manage your payments</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Payment List</h3>
              @if(auth()->user()->isCustomer() && $allow_create == true)
              <div class="box-tools">
                <a href="{{ action('PaymentController@create') }} " class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Add Payment Service</a>
              </div>
              @endif

              @if(auth()->user()->isCustomer() && $allow_create == false)
                <div class="box-tools">
                  <button href="#" class="btn btn-primary">
                  <font data-toggle="tooltip" data-placement="top" title="{{ $cutoff_string }}"> 
                   <i class="fa fa-plus"></i><strike>Add Payment Service</strike>
                    </font></button>
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
                  <th>Total Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($payments as $payment)
                <tr>
                 <td>{{ $payment->id }}</td>
                 <td>PHP {{ number_format($payment->total_amount(),2) }}</td>
                 <td><span class="label bg-orange">{{ $payment->getStatusDisplay() }}</span></td>
                 <td>
                    <a href="#" class="modal_button btn" data-href="{{ action('PaymentController@show', [$payment->id] ) }}"><i class="fa fa-eye"></i> View</a>
                    @if($payment->status ==  3 && request()->user()->isSupplier())
                    <a class="btn" href="{{ action('PaymentController@addSupplierDetails', [$payment->id] ) }}"><i class="fa fa-plus"></i> Add Supplier Details</a>
                    @endif
                    @if($payment->status ==  5 && request()->user()->isCustomer())
                    <a class="btn" href="{{ action('PaymentController@addSupplierDetails', [$payment->id] ) }}"><i class="fa fa-edit"></i> Edit Payment Details</a>
                    @endif
                    @if($payment->isTimeFrameAllowed() == true)
                    @if($payment->status ==  4 && request()->user()->isCustomer())
                    <a class="btn confirmation" data-href="{{ action('PaymentController@approvePayment', [$payment->id] ) }}" data-title="Are you sure to approve amount for this transaction?"><i class="fa fa-check"></i> Approve Amount: PHP {{ number_format($payment->total_amount(), 2) }}</a>
                    @endif
                    @if($payment->status == 5 && auth()->user()->isCustomer())
                    <a href="#" class="btn modal_button" data-href="{{ action('PaymentController@getDeposit', $payment->id) }}"><i class="fa fa-edit"></i> {{ $payment->deposit ? 'Update' : 'Add' }} Deposit Proof</a>
                    @endif  
                    @else
                    @if($payment->status != 7)
                    <font data-toggle="tooltip" data-placement="top" title="{{ $payment->getCutOffString() }}">
                    <i class="fa fa-info-circle"></i><a href="#" class="btn"><strike>Cut off</strike></a>
                    </font>
                    @endif
                    @endif
                    @if($payment->status == 6 && auth()->user()->isAdmin())
                    <a href="#" class="btn modal_button" data-href="{{ action('PaymentController@getPaymentConfirm', $payment->id) }}"><i class="fa fa-check"></i>Confirm Payment / Submit Screenshot of Deposit</a>
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
        "targets": [2,3],
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
