@extends('layouts.global')

@section('title')
    - Profile Management
@endsection

@section('page-header')
    Profile Management
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="card card-primary">
        <div class="card-body card-profile text-center">
          <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/users_images/' . $user->image) }}" alt="User profile picture">

          <h3 class="profile-username text-center">{{ $user->name }} {{ $user->username ? '- ' . $user->username : '' }}</h3>

          {{-- Delete Photo Profile --}}    
            <form action="{{ route('user.deletePhotoProfile', $user->id) }}" method="POST" onsubmit="return confirm('Delete Photo Profile?')">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <button type="submit" class="btn btn-warning btn-block">
                    Delete Your Image 
                </button>
            </form>
            <hr>

            <h3 class="text-center text-danger">Danger Zone</h3>
            <form action="{{ route('user.delete-account', $user->id) }}" method="POST" onsubmit="return confirm('Are You sure want to delete Your account? This cant be revert!')">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger btn-block">
                    Delete Your Account
                </button>
            </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->

    <div class="col-md-9">
      <div class="card card-primary card-solid">
        <div class="card-header">
            <div class="card-title">
                <div class="pull-left">
                    Change Your Profile
                </div>
            </div>
        </div>
        
        <div class="card-body">
            {!! Form::open(['route' => ['user.changeProfile', $user->id], 'method' => 'PUT', 'onsubmit' => 'return confirm("Change Your Profile ?")', 'enctype' => 'multipart/form-data' ]) !!}

                <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('nip') ? 'has-error' : '' }}">
                    {{ Form::label('nip', 'NIP') }}
                    {{ Form::text('nip', $user->nip, ['class' => 'form-control', 'placeholder' => 'Your NIP', 'autocomplete' => 'off']) }}
                    <div class="help-block">
                        {{ $errors->first('nip') }}
                    </div>
                </div>

                <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('name') ? 'has-error' : '' }}">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => 'Your Name', 'autocomplete' => 'off']) }}
                    <div class="help-block">
                        {{ $errors->first('name') }}
                    </div>
                </div>

                <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('divisi') ? 'has-error' : '' }}">
                    {{ Form::label('divisi', 'Division') }}
                    <select class="form-control" name="divisi" id="division-select"></select>
                    <div class="help-block">
                        {{ $errors->first('divisi') }}
                    </div>
                </div>

                <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('phone') ? 'has-error' : '' }}">
                    {{ Form::label('phone', 'Phone') }}
                    {{ Form::number('phone', $user->phone, ['class' => 'form-control', 'placeholder' => 'Your Phone', 'autocomplete' => 'off']) }}
                    <div class="help-block">
                        {{ $errors->first('phone') }}
                    </div>
                </div>
                
                <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('email') ? 'has-error' : '' }}">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Your Email', 'autocomplete' => 'off']) }}
                    <div class="help-block">
                        {{ $errors->first('email') }}
                    </div>
                </div>

                <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('email_secondary') ? 'has-error' : '' }}">
                    {{ Form::label('email_secondary', 'Email Secondary [ Optional ]') }}
                    {{ Form::text('email_secondary', $user->email_secondary, ['class' => 'form-control', 'placeholder' => 'Your Email Secondary', 'autocomplete' => 'off']) }}
                    <div class="help-block">
                        {{ $errors->first('email_secondary') }}
                    </div>
                </div>

                @if($user->user_has_level->name == "C-LEVEL")
                    <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('level') ? 'has-error' : '' }}">
                        {{ Form::label('level') }}
                        <select name="level" id="level-select" class="form-control"></select>
                        <div class="help-block">
                            {{ $errors->first('level') }}
                        </div>
                    </div>
                @else 
                    <div class="form-group {{ $user->exists ? 'has-success' : '' }} {{ $errors->first('level') ? 'has-error' : '' }}">
                        {{ Form::label('level', 'Level') }}
                        {{ Form::text('level', $user->user_has_level->name, ['class' => 'form-control', 'placeholder' => 'Level', 'autocomplete' =>' off', 'readonly']) }}
                    </div>
                @endif

                {{ Form::hidden('status', $user->status) }}

                <div class="form-group">
                    {{ Form::label('image', 'Image [Optional]')}}
                    {{ Form::file('image') }}
                </div>

                {{ Form::submit('Change Profile', ['class' => 'btn btn-primary pull-right'])}}
                <a href="{{ route('user.change-password', $user->id) }}" class="btn btn-info btb-block pull-left">Change Password</a>
            {!! Form::close() !!}
        </div>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
@endsection
@push('script')
<script>
    $(function() {
    let user = {!! $user !!}
    $('#division-select').select2({
      theme: 'bootstrap4',
      placeholder: 'Choose Division',
    })

    $('#level-select').select2({
      theme: 'bootstrap4',
      placeholder: 'Choose Level',
    })

    $.ajax({
      url: '{{ route("levels.get") }}',
      type: 'GET',
      success: function(levels) {
        levels.forEach(level => {
          if(user.level_id == level.id) {
            $('#level-select').append(`<option value="${level.id}" selected>${level.name}</option>`)
          } else {
            $('#level-select').append(`<option value="${level.id}">${level.name}</option>`)
          }
        })
      },
      error: function(xhr) {
        let error = xhr.responseJSON
        toastr.error(error.message, 'ERROR')
      }
    })

    $.ajax({
      url: '{{ route("divisions.get") }}',
      type: 'GET',
      success: function(divisions) {
        divisions.forEach(division => {
          if(user.division_id == division.id) {
            $('#division-select').append(`<option value="${division.id}" selected>${division.name}</option>`)
          } else {
            $('#division-select').append(`<option value="${division.id}">${division.name}</option>`)
          }
        })
      },
      error: function(xhr) {
        let error = xhr.responseJSON
        toastr.error(error.message, 'ERROR')
      }
    })
  })
</script>
@endpush