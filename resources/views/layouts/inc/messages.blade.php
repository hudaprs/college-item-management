@if(session('success'))
    <div class="alert alert-success alert-dismissible">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <h4><em class="icon fa fa-check"></em> Success!</h4>
	    {{ session('success') }}
	</div>
@elseif(session('error'))
    <div class="alert alert-danger alert-dismissible">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <h4><em class="icon fa fa-ban"></em> ERROR!</h4>
	    {{ session('error') }}
  	</div>
@endif