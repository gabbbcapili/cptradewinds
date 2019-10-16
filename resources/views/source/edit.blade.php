 @inject('request', 'Illuminate\Http\Request')
<div class="modal-dialog modal-lg" role="document">
	<form action="{{ action('SourceController@update', [$source->id]) }}" method="POST" class="form" enctype='multipart/form-data'>
    @method('PUT')
		@csrf
  <div class="modal-content">
  	<div class="modal-header">
		<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="modalTitle">Edit Source
		</h4>
	</div>
<div class="modal-body">
       <div class="row">
          <div class="col-sm-4">
            <label>Name:*</label>
            <input type="text" class="form-control" name="name" value="{{ $source->name }}">
          </div>
        </div>    
  </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary no-print btn_save"><i class="fa fa-save"></i> Update
      </button>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
  </div>
  </form>
</div>
<script src="{{ asset('js/forms/load-courses.js') }}"></script>
<script src="{{ asset('js/forms/load-sections.js') }}"></script>
<script src="{{ asset('js/forms/form-modal.js') }}"></script>