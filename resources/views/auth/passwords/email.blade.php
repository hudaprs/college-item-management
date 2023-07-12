<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Item Management | Forget Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
  {{-- Pace --}}
  <link rel="stylesheet" href="{{ asset('plugins/pace/pace-theme-flash.css') }}">
  {{-- Toastr --}}
  <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
   {{-- Icon --}}
  <link rel="shortcut icon" type="image/x-icon" href="/icon.ico">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>project</b>OPS</a>
  </div>
  <!-- /.login-logo -->
  @if (session('status'))
  <div class="alert alert-success" role="alert">
      {{ session('status') }}
  </div>
@endif
  <div class="card">
    <div class="card-body login-card-body">
            <p class="login-box-msg">Reset Password Via E-Mail</p>

      <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <div class="row">
                    <label for="email" class="col-md-12">{{ __('E-Mail Address') }}</label>
                </div>
                <div class="row">
                <div class="col-md-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12 offset-md-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

{{-- Pace JS--}}
<script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
{{-- Toastr --}}
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script>
  toastr.options = {
       "closeButton": false,
       "debug": false,
       "newestOnTop": false,
       "progressBar": true,
       "positionClass": "toast-top-right",
       "preventDuplicates": true,
       "onclick": null,
       "showDuration": "300",
       "hideDuration": "1000",
       "timeOut": "5000",
       "extendedTimeOut": "1000",
       "showEasing": "swing",
       "hideEasing": "linear",
       "showMethod": "fadeIn",
       "hideMethod": "fadeOut"
   }
</script>
{{-- Script --}}
<script src="{{ asset('js/myscript.js') }}"></script>
<script>
  // Hide Disabled Login Button
  $('#btn-login-disabled').hide()

  // Init Pace
  $(document).ajaxStart(function() {
    Pace.restart();
  });
</script>
</body>
</html>
