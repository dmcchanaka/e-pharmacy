<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>e-Pharmacy</title>

    <link href="{{ asset('template/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('template/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{ asset('template/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">

    <!-- Gritter -->
    <link href="{{ asset('template/js/plugins/gritter/jquery.gritter.css')}}" rel="stylesheet">

    <link href="{{ asset('template/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css')}}" rel="stylesheet">
    @yield('css')
</head>

<body>

    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <img alt="image" class="rounded-circle" style="height: 80px" src="{{ asset('template/img/user.png') }}"/>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="block m-t-xs font-bold">Welcome {{ Auth::user()->name }}</span>
                            {{-- <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> --}}
                        </a>
                    </div>
                    <div class="logo-element">
                        EPMCY
                    </div>
                </li>
                <li>
                    <a href="{{ url('product') }}"><i class="fa fa-coffee"></i> <span class="nav-label">PRODUCT</span>  </a>
                </li>
                {{-- @if (config('pos.admin_user_type') == Auth::user()->u_tp_id) --}}
                <li>
                    <a href="#"><i class="fa fa-file"></i> <span class="nav-label">GRN </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ url('grn') }}">ADD</a></li>
                        <li><a href="{{ url('view_grn') }}">VIEW</a></li>
                    </ul>
                </li>
                {{-- @endif --}}
                <li>
                    <a href="#"><i class="fa fa-shopping-cart"></i> <span class="nav-label">INVOICE </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ url('invoice') }}">ADD</a></li>
                        <li><a href="{{ url('view_invoice') }}">VIEW</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-money"></i> <span class="nav-label">EXPENSES </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ url('expenses') }}">ADD</a></li>
                        <li><a href="{{ url('view_expenses') }}">VIEW</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-book"></i> <span class="nav-label">REPORTS </span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ url('reports/stock/load') }}">STOCK</a></li>
                        <li><a href="{{ url('reports/expiry-product/load') }}">EXPIRY PRODUCTS</a></li>
                        <li><a href="{{ url('reports/daily-sales-summary/load') }}">DAILY SALES SUMMARY</a></li>
                        <li><a href="{{ url('reports/doctor-payments/load') }}">DOCTOR PAYMENTS</a></li>
                        <li><a href="{{ url('reports/fast-moving-items/load') }}">FAST MOVING ITEMS</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group"></div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to e-Pharmacy</span>
                </li>
                <li>
                    <a href="{{ route('logout') }}" id="navbarDropdownMenuLink2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                    <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>

        </nav>
        </div>
        <div>
            @yield('content')
        </div>
        <div class="footer">
            <div class="float-right">
                designed by <strong>DMCC</strong> &copy; {{date('Y')}}
            </div>
        </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="{{ asset('template/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('template/js/popper.min.js')}}"></script>
    <script src="{{ asset('template/js/bootstrap.js')}}"></script>
    <script src="{{ asset('template/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{ asset('template/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Flot -->
    <script src="{{ asset('template/js/plugins/flot/jquery.flot.js')}}"></script>
    <script src="{{ asset('template/js/plugins/flot/jquery.flot.tooltip.min.js')}}"></script>
    <script src="{{ asset('template/js/plugins/flot/jquery.flot.spline.js')}}"></script>
    <script src="{{ asset('template/js/plugins/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{ asset('template/js/plugins/flot/jquery.flot.pie.js')}}"></script>

    <!-- Peity -->
    <script src="{{ asset('template/js/plugins/peity/jquery.peity.min.js')}}"></script>
    <script src="{{ asset('template/js/demo/peity-demo.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('template/js/inspinia.js')}}"></script>
    <script src="{{ asset('template/js/plugins/pace/pace.min.js')}}"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('template/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- GITTER -->
    <script src="{{ asset('template/js/plugins/gritter/jquery.gritter.min.js')}}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('template/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Sparkline demo data  -->
    <script src="{{ asset('template/js/demo/sparkline-demo.js')}}"></script>

    <!-- ChartJS-->
    <script src="{{ asset('template/js/plugins/chartJs/Chart.min.js')}}"></script>

    <!-- Toastr -->
    <script src="{{ asset('template/js/plugins/toastr/toastr.min.js')}}"></script>
    @yield('js')
    <!-- Page-Level Scripts -->
    <scri
    </script>

</body>

</html>
