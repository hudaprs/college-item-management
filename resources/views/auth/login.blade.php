<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Item Management | Log in</title>
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
    <a href="#"><b>Item</b>Management</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="{{ route('login') }}" method="post" id="login-form">
        @csrf
        <div class="form-group">
          <input type="email" id="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
          <input type="password" id="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat" id="btn-login" onclick="lsRememberMe()">Sign In</button>
            <button type="button" class="btn btn-primary btn-block btn-flat disabled" id="btn-login-disabled"><em class="fa fa-spin fa-refresh"></em></button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let rmCheck = document.getElementById("remember"),
emailInput = document.getElementById("email");
passwordInput = document.getElementById("password");

if (localStorage.checkbox && localStorage.checkbox != "") {
  rmCheck.setAttribute("checked", "checked");
  emailInput.value = localStorage.username;
  passwordInput.value = localStorage.password;
} else {
  rmCheck.removeAttribute("checked");
  emailInput.value = "";
}

function lsRememberMe() {
  if (rmCheck.checked && emailInput.value != "") {
    localStorage.username = emailInput.value;
    localStorage.password = passwordInput.value;
    localStorage.checkbox = rmCheck.value;
  } else {
    localStorage.username = "";
    localStorage.checkbox = "";
  }
}
</script>

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
