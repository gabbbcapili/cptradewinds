@extends('layouts.base')
@section('title', 'Customs Clearance')

@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header no-print">
      <h1>
        Customs Clearance
        <small>manage your customs clearance</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customs Clearance List</h3>
              @if(auth()->user()->isCustomer())
              <div class="box-tools">
                <a href="{{ action('ClearanceController@create') }} " class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Add Customs Clearance</a>
              </div>
              @endif
            </div>
            <!-- /.box-header -->

            <div class="box-body">
              <div class="table-responsive">
              <table id="order_list" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">Transaction#</th>
                  <th class="text-center">Date</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($clearances as $clearance)
                <tr>
                  <td>{{ $clearance->id }}</td>
                  <td>
                    @if(request()->user()->isAdmin() || request()->user()->isCustomer())
                   <a href="#" class="modal_button" data-href="{{ action('ClearanceController@show', [$clearance->id] ) }}">{{ $clearance->created_at }}</a>
                   @else
                   <a href="#" class="modal_button" data-href="{{ action('ClearanceController@showAdmin3', [$clearance->id] ) }}">{{ $clearance->created_at }}</a>
                   @endif
                 </td>
                  <td><span class="label bg-orange">{{ $clearance->get_status_display() }}</span></td>
                  <td>
                    @if(request()->user()->isAdmin() || request()->user()->isCustomer())
                    <a href="#" class="modal_button btn" data-href="{{ action('ClearanceController@show', [$clearance->id] ) }}"><i class="fa fa-eye"></i> View</a>
                    @endif
                    @if(request()->user()->isAdmin3())
                    <a href="#" class="modal_button btn" data-href="{{ action('ClearanceController@showAdmin3', [$clearance->id] ) }}"><i class="fa fa-eye"></i> View</a>
                    @endif
                    @if($clearance->status == 1 && auth()->user()->isAdmin3())
                        <a href="#" class="btn modal_button" data-href="{{ action('ClearanceController@addQuotation', $clearance->id) }}"><i class="fa fa-plus"></i> Add Quotation</a>
                    @endif
                    @if($clearance->status == 2 && $clearance->admin3_price != null && (request()->user()->isAdmin() || request()->user()->isAdmin3()))
                      <a href="#" class="btn modal_button" data-href="{{ action('ClearanceController@viewQuotation', $clearance->id) }}"><i class="fa fa-eye"></i> View Quotation</a>
                    @endif
                    @if($clearance->status == 3 && auth()->user()->isCustomer())
                        <a href="#" class="btn modal_button" data-href="{{ action('ClearanceController@customerDeposit', $clearance->id) }}"><i class="fa fa-plus"></i> Upload Deposit Slip</a>
                    @endif
                    @if($clearance->status == 4 && auth()->user()->isAdmin())
                        <a href="#" class="btn modal_button" data-href="{{ action('ClearanceController@admin1Deposit', $clearance->id) }}"><i class="fa fa-plus"></i> Upload Deposit Slip</a>
                    @endif
                     @if($clearance->status == 5 && auth()->user()->isAdmin3())
                        <a class="btn" target="_blank" href="{{ $clearance->get_admin1_deposit_url() }}">View Customer Deposit</a>
                    @endif
                    @if($clearance->status ==  5 && request()->user()->isAdmin3())
                    <a class="btn modal_button" data-href="{{ action('ClearanceController@addTrackingNo', [$clearance->id] ) }}"><i class="fa fa-check"></i> Confirm Deposit Slip / Add Tracking No.</a>
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
    $('#order_list').DataTable({
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
