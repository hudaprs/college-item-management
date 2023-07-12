<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Item Management @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  {{-- Icon --}}
  <link rel="shortcut icon" type="image/x-icon" href="/icon.ico">
  {{-- Csrf Token --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-timegrid/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  {{-- Toastr --}}
  <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
  {{-- Sweet Alert --}}
  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
  {{-- Pace Style --}}
  <link rel="stylesheet" href="{{ asset('plugins/pace/pace-theme-flash.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
      .avatar-circle-gits {
      width: 30px;
      height: 30px;
      background-color: #858687;
      text-align: center;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
    }
    .initials-gits {
      position: relative;
      top: 3px; /* 25% of parent */
      font-size: 15px; /* 50% of parent */
      line-height: 20px; /* 50% of parent */
      color: #fff;
      font-family: "Source Sans Pro";
      font-weight: bold;
    }
    #container {
    position: relative;
    }
    </style>
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}">
  @stack('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('/') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a href="{{ route('logout') }}" class="nav-link" id="sign-out"><em class="fa fa-sign-out nav-icon"></em>Sign Out</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-dark-light">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ asset('/images/users_images/noimage.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Item Management</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" id="container">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <div 
              class="avatar-circle-gits"
            >
                <span 
                  class="initials-gits"
                  style="font-size: 20px">
                  {{ strtoupper('A') }}
                </span>
            </div>
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          {{-- Dashboard URL --}}
          <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ url()->current() == url('/dev') ? 'active' : null }}"><em class="nav-icon fa fa-home"></em>
              <p>Dashboard</p>
            </a>
          </li>

          {{-- Managements --}}
          <li class="nav-item has-treeview
            @if(url()->current() == url('/dev/users'))
              {{ 'menu-open' }}
            @else
              {{ null }}
            @endif
          ">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-gear"></i>
              <p>
                User Managements
                <i class="right fa fa-angle-down"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ url()->current() == url('/dev/users') ? 'active': null }}">
                  <em class="fa fa-users nav-icon"></em>
                  <p>Users</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview
            @if(url()->current() == url('/dev/products'))
              {{ 'menu-open' }}
            @else
              {{ null }}
            @endif
          ">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-gear"></i>
              <p>
                Product Managements
                <i class="right fa fa-angle-down"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link {{ url()->current() == url('/dev/products') ? 'active': null }}">
                  <em class="fa fa-book nav-icon"></em>
                  <p>Products</p>
                </a>
              </li>
            </ul>
          </li>

          {{-- Logout --}}
          <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
            @csrf
          </form>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">@yield('page-header')</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        {{-- Message Session --}}
        @include('layouts.inc.messages')
        {{-- Modal --}}
        @include('layouts.inc.modal')

        @yield('content')
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Item Management Team
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2008 - {{ date('Y') }} <a href="https://github.com/hudaprs">Huda Prasetyo</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->
  <!-- REQUIRED JS SCRIPTS -->

  <!-- jQuery 3 -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
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
  {{-- Sweet Alert3 --}}
  <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
  {{-- Pace Js --}}
  <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
  {{-- Init Pace --}}
  <script>
    $(document).ajaxStart(function() {
     Pace.restart();
   });
  </script>
  <!-- Select2 -->
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('js/Chart.min.js') }}"></script>
  <!-- fullCalendar 2.2.5 -->
  <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>
  <script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js') }}"></script>
  <script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js') }}"></script>
  <script src="{{ asset('plugins/fullcalendar-interaction/main.min.js') }}"></script>
  <script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
  <!-- iCheck 1.0.1 -->
  <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
  @stack('script')
  {{-- My Script --}}
  <script src="{{ asset('js/myscript.js') }}"></script>
  <script src="{{ asset('js/perfect-scrollbar.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
  <script>
    $(function() {
      const container = document.querySelector('#container');
      const ps = new PerfectScrollbar(container);
      $('#sign-out').on('click', function(event) {
        event.preventDefault()

        let url = $(this).attr('href'),
            form = $('#logout-form')

        Swal.fire({
          title: 'Sign Out Now ? ',
          text: "If you have a unsaved changes, you will lose it.",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Sign Out!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: url,
              data: form.serialize(),
              method: 'POST',
              beforeSend: () => {
                toastr.warning('Sign Out In Process', 'WARNING')
              },
              success: () => {
                toastr.success('Sign Out Successfull, Please Wait', 'SUCCESS')
                window.location.href = '/'
              },
              error: () => {
                toastr.error('Sign Out Failed', 'ERROR')
              }
            })
          }
        })
      })
    })
  </script>
</body>

</html>