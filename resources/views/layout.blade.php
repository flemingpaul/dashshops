<!DOCTYPE html>

<html lang="">

<head>

    <title>The DashShop Web Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>


    <script type="text/javascript" src="../js/app.js"></script>
    <style type="text/css">
        body {

            margin: 0;

            font-size: .9rem;

            font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;

            font-weight: 400;

            line-height: 1.6;

            color: #212529;

            text-align: left;

            background-color: #f5f8fa;

        }

        .navbar-light {
            background-color: #E89342;
        }

        .navbar-laravel {

            box-shadow: 0 2px 4px rgba(0, 0, 0, .04);

        }

        .navbar-brand,
        .nav-link,
        .my-form,
        .login-form {

            font-family: Raleway, sans-serif;

        }

        .my-form {

            padding-top: 1.5rem;

            padding-bottom: 1.5rem;

        }

        .my-form .row {

            margin-left: 0;

            margin-right: 0;

        }

        .login-form .row {

            margin-left: 0;

            margin-right: 0;

        }

        .input-group-append {
            background-color: #FFFFFF;
            !important;
        }

        .field-icon {
            float: right;
            margin-left: -25px;
            margin-right: 10px;
            margin-top: -25px;
            position: relative;
            z-index: 2;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">

</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">

        <div class="container">

            <a class="navbar-brand text-white font-weight-bold" href="/">
                <img src="{{asset('logo1.png')}}" alt="logo" width="40%">
                <span class="pl-3 st-4">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ml-5 text-white" style="display: flex; flex-direction: row; justify-content: space-around;">

                    @guest

                    <li class="nav-item mr-5">

                        <a class="nav-link text-white" href="#">Why The DashShop</a>

                    </li>
                    <li class="nav-item mr-5">

                        <a class="nav-link text-white" href="#">How it Works</a>

                    </li>
                    <li class="nav-item mr-5">

                        <a class="nav-link text-white" href="#">By Industry</a>

                    </li>
                    <li class="nav-item mr-5">

                        <a class="nav-link text-white" href="{{ route('login') }}">Login</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link btn btn-light pl-4 pr-4" style="border-radius: 25px; color: black" href="{{ route('register') }}">Get Started</a>

                    </li>

                    @else

                    <li class="nav-item ml-4">

                        <a class="nav-link text-white text-right" href="{{ route('logout') }}">Logout</a>

                    </li>

                    @endguest

                </ul>


            </div>

        </div>

    </nav>


    @yield('content')
    <footer>
        <div class="container-fluid" style="background-color: #3C9282">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <a href="https://thedashshop.com">
                        <img src="{{asset('images/logo.png')}}" alt="logo" width="30%" style="margin: 20%">
                    </a>
                    <p class="text-white" width="30%" style="text-align: center; margin-left: 10%" style="margin-left: 10%" href="#;">Competitive marketing exclusively offered to local business owners at an affordable price.</p>
                </div>
                <div class="col-md-4 justify-content-center">
                    <ul style="text-decoration: none; list-style: none; margin-top: 10%">
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">Why The DashShop Discounts</a>

                        </li>
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">How it Works</a>

                        </li>
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">By Industry</a>

                        </li>
                        <li class="nav-item">

                            <a class="nav-link text-white" href="#">Signup Now!</a>

                        </li>
                    </ul>
                </div>
                <div class="col-md-4 justify-content-center">
                    <h4 class="text-white mb-4" style="margin-top: 10%"> Subscribe for Updates</h4>
                    <form action="mailto:support@thedashshop.com" method="POST" enctype="text/plain">
                        <div style="display: flex; flex-direction: row">
                            <input style="width: 300px; border-radius: 15px; border: thin grey" type="email" id="newsletter" name="newsletter" placeholder="Email">
                            <img src="{{asset('images/right.png')}}" width="8%" alt="email" style="
                                float: right;
                                margin-left: -55px;
                                margin-top: 3px;
                                margin-bottom: 3px;
                                position: relative;
                                z-index: 2;" />
                        </div>
                    </form>
                    <div style="display: flex; flex-direction: row; margin-top: 15%">
                        <a class="text-white" href="{{route('terms')}}">Terms of Use</a>
                        <a class="text-white" href="{{route('privacy')}}" style="margin-left: 5%">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>


</body>

</html>