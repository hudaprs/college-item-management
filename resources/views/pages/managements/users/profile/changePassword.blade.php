@extends('layouts.global')

@section('title')
    -  Change Password
@endsection

@section('page-header')
    Change Password
@endsection

@section('content')

    <div class="card card-solid card-warning">
        <div class="card-header">
            <div class="card-title">
                <div class="pull-left">
                    Change Your Password
                </div>
            </div>

            <div class="pull-right">
                <a href="#" class="btn btn-default">Go Back</a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('user.password-change', $user->id) }}" method="POST" onsubmit="return confirm('Change Your password?')">
                @csrf
                <input type="hidden" name="_method" value="PUT">
        
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}" placeholder="New Password">
                    <div class="text-danger">
                        {{ $errors->first('password') }}
                    </div>
                </div>
        
                <div class="form-group">
                    <label for="password_confirmation">New Password Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control {{ $errors->first('password_confirmation') ? 'is-invalid' : '' }}" placeholder="New Password Confirmation">
                    <div class="text-danger">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block"> Change Your Password</button>
            </form>
        </div>
    </div>

@endsection