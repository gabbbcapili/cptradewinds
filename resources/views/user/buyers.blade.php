@extends('layouts.base')
@section('title', 'Buyers')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
      <h1>
        Buyers
        <small>manage your buyers</small>
      </h1>
    </section>
    
    <!-- Main content -->
    <section class="content no-print">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of buyers</h3>
            </div>
            <div class="box-body">
              <div class="table-responsive">
              <table id="buyers_list" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">First Name</th>
                  <th class="text-center">Last Name</th>
                  <!-- <th class="text-center">Logs</th> -->
                  <th class="text-center">Email</th>
                  <th class="text-center">Mobile Number</th>
                  <th class="text-center">City</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($buyers as $user)
                    <tr>
                        <td class="text-center">{{ $user->name }}</td>
                        <td class="text-center">{{ $user->last_name }}</td>
                        <td class="text-center">{{ $user->email }}</td>
                        <td class="text-center">{{ $user->phone_no }}</td>
                        <td class="text-center">{{ $user->city }}</td>
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
    $('#buyers_list').DataTable();
  });
</script>
 @endsection
 
