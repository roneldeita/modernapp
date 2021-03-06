<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->

    <link href="{{ url('/assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ url('/css/app.css') }}" rel="stylesheet">

    <link href="{{ url('/css/admin.css') }}" rel="stylesheet">

    <link href="{{ url(Auth::user()->theme->css_path) }}.css" rel="stylesheet" type="text/css">
    @yield('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">

        <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                
                <nav id="spy">
                    <ul class="sidebar-nav nav">
                        <li>
                            <div class="user-panel text-center">
                                <img class="img-circle" src="{{ url(Auth::user()->profile_picture) }}"/>
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                        </li>
                        <li>
                            <a href="{{ url('/admincontrol') }}"><span class="fa fa-dashboard"></span> Dashboard</a>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ url('/admincontrol/post') }}" aria-expanded="false">
                                <span class="fa fa-comments"></span> Post <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="{{ url('/admincontrol/post') }}">View All</a></li>
                              <li><a href="{{ url('/admincontrol/post/category') }}">Category</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ url('/admincontrol/access') }}"><span class="fa fa-id-card"></span> Access</a>
                        </li>
                        <li>
                            <a href="{{ url('/admincontrol/user') }}"><span class="fa fa-user-circle"></span> User</a>
                        </li>
                        <li>
                            <a href="{{ url('/admincontrol/appearance') }}"><span class="fa fa-paint-brush"></span> Appearance</a>
                        </li>
                        <li class="divider">
                            <hr>
                        </li>
                        <li>
                            <a href="{{ url('/') }}"><span class="fa fa-home"></span> Home</a>
                        </li>
                        <li>
                            <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="fa fa-sign-out"></span> Logout
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Page content -->
            <div id="page-content-wrapper">

                <div class="content-header">

                    <h1 id="home">
                        <a id="menu-toggle" href="#">
                            <i class="fa fa-bars"></i>
                        </a>
                    </h1>
                    
                </div>

                <div class="page-content">

                    @yield('content')

                </div>

            </div>

        </div>
        
    </div>

    <!-- Scripts -->
    <script src="{{ url('/js/app.js') }}"></script>
    @yield('scripts')

    <script type="text/javascript">

        /*Menu-toggle*/
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("active");
        });

    </script>

</body>
</html>
