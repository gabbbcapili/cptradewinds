@inject('request', 'Illuminate\Http\Request')
@extends('layouts.base')
@section('title', 'Source')

@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header no-print">
      <h1>
        Source
        <small>create sources</small>
      </h1>
    </section>

<form action="{{ action('SourceController@store') }}" class="form" method="POST" enctype="multipart/form-data">
    @csrf
    <section class="content">
      <div class="box box-solid">
          <div class="container-fluid">
            <h3>Source Details:*</h3>
          </div>
      <div class="box-body">
        <div class="row">
          <div class="col-sm-4">
            <label>Name:*</label>
            <input type="text" class="form-control" name="name">
          </div>
        </div>
      </div>
    </div> <!--box end-->

  <div class="box box-solid">
    <div class="box-body">
      <div class="center-div">
        <input type="submit" name="saveandadd" class="btn btn-warning btn-lg btn_save margin-r-10" value="Save and Add Another">
        <input type="submit" name="save" class="btn btn-primary btn-lg btn_save" value="Save Source">
      </div>
    </div>
  </div> <!--box end-->
  </section>
</form>

@endsection
@section('javascript')
<script src="{{ asset('js/forms/form-normal.js') }}"></script>

@endsection