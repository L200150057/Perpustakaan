<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/fontawesome/fontawesome/css/font-awesome.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
</head>
<body id="particles-js">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top" id="flat">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <div id="{{ (Request::is('/*') ? 'active' : '') }}">Home</div>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}" style="{{ (Request::is('login*') ? 'color: rgb(66, 157, 209);' : '') }}">Login</a></li>
                            {{-- <li><a href="{{ route('register') }}">Register</a></li> --}}
                        @else
                            <li>
                                  <a href="{{ route('data.index') }}" id="{{ (Request::is('data*') ? 'active' : '') }}">Data</a>
                            </li>
                            <li>
                                  <a href="{{ route('export') }}" id="{{ (Request::is('export*') ? 'active' : '') }}">Export</a>
                            </li>
                            <li class="dropdown">
                                <a id="username" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                          <a href="{{ route('user.show', Auth::user()->id) }}">Setting</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/particles.js') }}"></script>
    <script src="{{ asset('js/particle-app.js') }}"></script>
    <script type="text/javascript">

        $('#dropdown-search li a').click(function(){
            $('#selected').text($(this).text());
            $('#mbuh').attr('name', $(this).attr('name'));
        })

        @if(session('success'))
            $.notify({
                message: "{{ session('success') }}"
            },{
                type: 'success',
                delay: 1000
            });
        @endif

        @if(session('error'))
            $.notify({
                message: "{{ session('error') }}"
            },{
                type: 'danger',
                delay: 3000
            });
        @endif

        @foreach ($errors->all() as $error)
        $.notify({
            message: "{{ $error }}"
        },{
            type: 'danger',
            delay: 3000
        });
        @endforeach

        $('ul.dropdown-menu li a').click(function() {
            $('#selected').text($(this).text());
            $('#mbuh').attr('name', $(this).attr('name'));
        })

    </script>
</body>
</html>
