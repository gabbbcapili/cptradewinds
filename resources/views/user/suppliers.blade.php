@extends('layouts.base')
@section('title', 'Suppliers')


@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
      <h1>
      Suppliers
        <small>manage your suppliers</small>
      </h1>
    </section>
    
    <!-- Main content -->
    <section class="content no-print">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of suppliers</h3>
            </div>
            <div class="box-body">
              <div class="table-responsive">
              <table id="suppliers_list" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">First Name</th>
                  <th class="text-center">Last Name</th>
                  <!-- <th class="text-center">Logs</th> -->
                  <th class="text-center">Email</th>
                  <th class="text-center">Date of Joining</th>
               
                </tr>
                </thead>
                <tbody>
                @foreach ($suppliers as $user)
                
                <tr>
                   
                        <td class="text-center">{{ $user->name }}</td>
                        <td class="text-center">{{ $user->last_name }}</td>
                        <td class="text-center">{{ $user->email }}</td>
                   <td class="text-center">{{ $user->created_at }}</td>
                 
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
    @endsection
    @section('javascript')
<script type="text/javascript">
$(document).ready( function(){
    $('#suppliers_list').DataTable();
  });
</script>
 @endsection
