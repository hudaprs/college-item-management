{!! 
	Form::model($user, [
		'route' => isset($user) && $user->exists ? ['users.update', $user->id] : 'users.store',
		'method' => isset($user) && $user->exists ? 'PUT' : 'POST',
		'enctype' => 'multipart/form-data'
	])
!!}

  <div class="row">
    <div class="col-md-12">
    	<div class="form-group">
    		{{ Form::label('name', 'Name') }}
    		{{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'User Name', 'autocomplete' => 'off']) }}
    	</div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
    	<div class="form-group">
    		{{ Form::label('email', 'Email') }}
    		{{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'User Email', 'autocomplete' => 'off']) }}
    	</div>
    </div>
  </div>

  <div class="row">
  	@if(isset($user) && $user->exists === false)
      <div class="col-md-6">
    		<div class="form-group">
    			{{ Form::label('password', 'Password') }}
    			{{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off']) }}
    		</div>
      </div>

      <div class="col-md-6">
    		<div class="form-group">
    			{{ Form::label('password_confirmation', 'Password Confirmation') }}
    			{{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password Confirmation', 'autocomplete' => 'off']) }}
    		</div>
      </div>
  	@endif
  </div>

  <div class="row">
    <div class="col-md-12">
    	<div class="form-group">
    		{{ Form::label('role', 'Role') }}
    		{{ Form::select('role', ['' => 'Choose Role', 'Admin' => 'Admin', 'User' => 'User'], null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
    	</div>
    </div>
  </div>

{!! Form::close() !!}

<script>
	$(function () {
		$('#role').select2({placeholder: 'Choose Role'})
	})
</script>