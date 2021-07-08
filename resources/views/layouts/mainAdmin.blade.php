<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
        integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
        crossorigin="anonymous" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @yield('third_party_stylesheets')

    @stack('page_css')
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href={{ route('customer.index') }} class="nav-link">
                                <i class="nav-icon fa fa-user-tag"></i>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href={{ route('crew.index') }} class="nav-link">
                                <i class="nav-icon fa fa-book"></i>
                                Onawa Crew
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href={{ route('cat.index') }} class="nav-link">
                                <i class="nav-icon fa fa-cat"></i>
                                Cats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href={{ route('grooming.report') }} class="nav-link">
                                <i class="nav-icon fa fa-poll"></i>
                                Grooming Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href={{ route('grooming_type.index') }} class="nav-link">
                                <i class="nav-icon fa fa-viruses"></i>
                                Grooming Types
                            </a>
                        </li>
                    </ul>

                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item text-navy">
                       <a class="nav-link">
                           {{ ucfirst(Auth::user()->name) }}
                        </a>
                    </li> 
                    <li class="nav-item text-navy">
                        <a class="nav-link" href="#" role="button"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <span class="m-5"></span>
                <div class="container-fluid">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <div class="d-flex align-self-center align-items-center">
                                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if (Session::get('status_success'))
                        <div class="alert alert-success alert-dismissible align-self-center align-items-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Nice!</h5>
                            {{ Session::get('status_success') }}
                        </div>
                    @endif
                    @if (Session::get('status_error'))
                        <div class="alert alert-danger alert-dismissible align-self-center align-items-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-close"></i> Ups!</h5>
                            {{ Session::get('status_eror') }} Error, please report to devloper team
                        </div>
                    @endif
                    @if (Session::get('status_error_custom'))
                        <div class="alert alert-danger alert-dismissible align-self-center align-items-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-close"></i> Ups!</h5>
                            {{ Session::get('status_error_custom') }}
                        </div>
                    @endif

                    @yield('content')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>
    </div>

    <script src="{{ mix('js/app.js') }}" defer></script>

    @stack('third_party_scripts')

</body>

</html>
