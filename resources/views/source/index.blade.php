@extends('layouts.base')
@section('title', 'Sources')

@section('content')
<style type="text/css">
  tbody{
    text-align: center;
  }
</style>
   <!-- Content Header (Page header) -->
    <section class="content-header no-print">
      <h1>
        Sources
        <small>manage your tags</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Sources List</h3>
                <div class="box-tools">
                  <div class="btn-group">
                  <a class="btn btn-block btn-primary" href="{{ action('SourceController@create') }} "><i class="fa fa-plus"></i> Add Source</a>
                </div>
              </div>
            </div> <!-- /.box-header -->
             <div class="box-body">
              <div class="table-responsive">
              <table id="table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">Quotations Count</th>
                  <th class="text-center">Shipment Count</th>
                  <th class="text-center">Name</th>
                   <th class="text-center">Action</th>
                </tr>
                </thead>
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
 $( document ).ready(function() {
  var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('source.index') }}',
        columns: [
            { data: 'quotations', name: 'quotations' },
            { data: 'shipments', name: 'shipments' },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable : false},
        ]
    });
  $('[data-toggle="tooltip"]').tooltip();

  $('.view_modal').on('hidden.bs.modal', function () {
    table.ajax.reload();
  });

  $(document).on('click','.copy',function(){
  // Create a "hidden" input
    var aux = document.createElement("input");
    aux.setAttribute("value", $(this).data('copy'));
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux); 
    toastr.success('Link copied to clipboard.');
  });
});
 </script>

 <script type="text/javascript">


   function copyToClipboard(text) {

  // Create a "hidden" input
  var aux = document.createElement("input");

  // Assign it the value of the specified element
  aux.setAttribute("value", text);

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
@endsection
