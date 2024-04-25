<!DOCTYPE html>

<html lang="">

<head>

    <title>The DashShop Web Portal</title>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- dropbox CDN  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/dropzone.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>

    <link href="https://fonts.cdnfonts.com/css/open-sans" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">




    <script>
        $(function() {

            $(".progress").each(function() {

                const value = $(this).attr('data-value');
                const left = $(this).find('.progress-left .progress-bar');
                const right = $(this).find('.progress-right .progress-bar');

                if (value > 0) {
                    if (value <= 50) {
                        right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
                    } else {
                        right.css('transform', 'rotate(180deg)')
                        left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
                    }
                }

            })

            function percentageToDegrees(percentage) {
                return percentage / 100 * 360
            }

        });
        var base_url = '<?php echo \App::make('url')->to(''); ?>';
        var get_base_url = function(route) {
            return base_url + "/" + route;
        }
    </script>

    @yield('extra_css')

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">

        <div class="container">

            <a class="navbar-brand font-weight-bold" href="/dashboard/analytics">
                <img src="{{asset('images/logos.png')}}" alt="logo" width="65%">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ml-auto text-white">

                    @guest

                    <li class="nav-item">

                        <a class="nav-link" href="{{ route('login') }}">Login</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link btn btn-light pl-4 pr-4" style="border-radius: 25px; color: black" href="{{ route('register') }}">Get Started</a>

                    </li>

                    @else

                    <li class="nav-item mr-3" style="width: 40px;">
                        <a href="#" class="nav-link nav-icon iconClass" style="margin: 22px">
                            <img src="{{asset('images/bell.png')}}" alt="bell" width="25" height="25">
                            <span class="badge badge-light">2</span>
                        </a>
                    </li>
                    <div class="dropdown show">
                        <a style="border: none" class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{asset('images/admin.png')}}" alt="logo" width="50%">
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @if(auth()->user()->admin == 1)
                            <a class="dropdown-item" href="{{ route('admin-portal') }}">Admin Settings</a>
                            <a class="dropdown-item" href="{{ route('categories') }}">Manage Categories</a>

                            <a class="dropdown-item" href="{{ route('ads') }}">Manage Ads</a>
                            @endif
                            @if(auth()->user()->admin == 2)
                            <a class="dropdown-item" href="{{ route('edit-user') }}">Edit Profile</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        </div>
                    </div>
                    <li class="nav-item text-black-50 mt-4">
                        {{auth()->user()->firstname}}<br>
                        <span class="text-black" style="font-size: smaller;">{{auth()->user()->user_type}}</span>
                    </li>
                    @endguest

                </ul>


            </div>

        </div>

    </nav>



    @yield('content')


    <footer class="mt-5">
        <div style="background-color: #E58821; height: 50px; width: 100%">

        </div>
        <div class="container-fluid">
            <div class="row" style="background-color: #3C9282">
                <div class="col-md-4">
                    <a href="https://thedashshop.com">
                        <img src="{{asset('images/logo.png')}}" alt="logo" width="30%" style="margin: 2%">
                    </a>
                </div>
                <div class="col-md-8">
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">ABOUT US</a>

                        </li>
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">EXPLORE</a>

                        </li>
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">JOIN VIP HUB</a>

                        </li>
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">CONTACT</a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{asset('js/functions.js?'.time())}}"></script>
    @yield('extra_js')
</body>

</html>