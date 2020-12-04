<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300" rel="stylesheet" type="text/css" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        svg {
            height: 36px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a class="navbar-brand" href="{{ url('/') }}">LOGO</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                @if (Auth::guest())
                    <li class="nav-item"><a class="nav-link" href="{{ url('/auth/login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/auth/register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <div class="dropdown-menu">
                            @if (Auth::user()->can_post())
                                <a class="dropdown-item" href="{{ url('/add-post') }}">Add Post</a>
                                <a class="dropdown-item" href="{{ url('/my-posts') }}">My Posts</a>
                            @endif
                            <a class="dropdown-item" href="{{ url('/user/'.Auth::id()) }}">My Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header1').submit();">{{__('Logout')}}</a>
                            <form id="logout-form-header1" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/import-posts') }}">Import Posts</a></li>
                @endif
            </ul>
        </div>
    </nav>
    <div class="container" style="margin-top:30px">
        @if (Session::has('message'))
            <div class="flash alert-info">
                <p class="panel-body">
                    {{ Session::get('message') }}
                </p>
            </div>
        @endif

        @if ($errors->any())
            <div class='flash alert-danger'>
                <ul class="panel-body">
                    @foreach ( $errors->all() as $error )
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>@yield('title')</h2>
                        @yield('title-meta')
                    </div>
                    <div class="panel-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="jumbotron text-center" style="margin: 5px; padding: 5px;">
        Copyright &copy; {{ date('Y') }} | <a href="https://www.katkamravikanth.com">Katkam Ravikanth</a>
    </div>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>