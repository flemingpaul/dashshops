<!DOCTYPE html>

<html lang="">

<head>

    <title>The DashShop Web Portal</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- dropbox CDN  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/dropzone.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
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


    <style type="text/css">
        /*

    body {

        margin: 0;

        font-size: .9rem;

        font-weight: 400;

        line-height: 1.6;

        color: #212529;

        text-align: left;

        background-color: #f5f8fa;

    }

    .navbar-light {
        background-color: #fff;
    }

    .navbar-laravel {

        box-shadow: 0 2px 4px rgba(0, 0, 0, .04);

    }

    .navbar-brand, .nav-link {

        font-family: Raleway, sans-serif;

    }*/

        /*.card-body .nav-item .nav-link{*/
        /*    background-image: url(/images/analytics.png);*/
        /*    background-size: contain;*/
        /*}*/
        /*
    .nav-link {
        list-style: none;
        color: black;
        text-decoration: none;
    !important;
        padding: 12px;
    }

    input {
        border: 1px solid black;
    }

    .field-icon {
        float: right;
        margin-left: -25px;
        margin-right: 10px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }

    .iconClass{
        position: relative;
    }
    .iconClass span{
        position: absolute;
        top: 7px;
        right: 0;
        left: 23px;
        display: block;
    }
    .progress {
        width: 120px;
        height: 120px;
        background: none;
        position: relative;
    }

    .progress::after {
        content: "";
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 6px solid #eee;
        position: absolute;
        top: 0;
        left: 0;
    }

    .progress > span {
        width: 50%;
        height: 100%;
        overflow: hidden;
        position: absolute;
        top: 0;
        z-index: 1;
    }

    .progress .progress-left {
        left: 0;
    }

    .progress .progress-bar {
        width: 100%;
        height: 100%;
        background: none;
        border-width: 6px;
        border-style: solid;
        position: absolute;
        top: 0;
    }

    .progress .progress-left .progress-bar {
        left: 100%;
        border-top-right-radius: 80px;
        border-bottom-right-radius: 80px;
        border-left: 0;
        -webkit-transform-origin: center left;
        transform-origin: center left;
    }

    .progress .progress-right {
        right: 0;
    }

    .progress .progress-right .progress-bar {
        left: -100%;
        border-top-left-radius: 80px;
        border-bottom-left-radius: 80px;
        border-right: 0;
        -webkit-transform-origin: center right;
        transform-origin: center right;
    }

    .progress .progress-value {
        position: absolute;
        top: 0;
        left: 0;
    }
    .analytics a:hover{
        color: #FFFFFF;
        background-image: url("/images/analyticbg.png");!important;
    }

    .retailer a:hover{
        color: #FFFFFF;
        background-image: url("/images/retailers.png");!important;
    }
    .retailer a:focus{
        color: #FFFFFF;
        background-image: url("/images/retailerbg.png");!important;
    }
    .coupon a:hover{
        color: #FFFFFF;
        background-image: url("/images/usersbg.png");!important;
    }
    .coupons a:hover{
        color: #FFFFFF;
        background-image: url("/images/coupons.png");!important;
        background-size: cover;
    }
    .add-retailer a:hover{
        color: #FFFFFF;
        background-image: url("/images/retailerbg.png");!important;
        background-size: cover;
    }
    .users a:hover{
        color: #FFFFFF;
        background-image: url("/images/usersbg.png");!important;
        background-size: cover;
    }
    ul.nav.nav-pills li.active a{
        color: #FFFFFF;
        background-image: url("/images/usersbg.png");!important;
        background-size: cover;
    }*/
    </style>

    <script>
        $(function() {

            $(".progress").each(function() {

                var value = $(this).attr('data-value');
                var left = $(this).find('.progress-left .progress-bar');
                var right = $(this).find('.progress-right .progress-bar');

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
    </script>

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
                        <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{asset('images/admin.png')}}" alt="logo" width="50%">
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href={{ route('admin-portal') }}>Admin Settings</a>
                            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        </div>
                    </div>
                    <li class="nav-item text-black-50 mt-4">
                        {{auth()->user()->firstname}}
                    </li>
                    @endguest

                </ul>


            </div>

        </div>

    </nav>


    <div class="container-fluid" style="background-color: #efefef">
        <div class="row ml-5">
            <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                <div class="card justify-content-center" style="width: 250px">
                    <div class="card-body">
                        @include('partials.menu')
                        <script>
                            /* Code for changing active
                        link on clicking */
                            const btns =
                                $("#navigation .navbar-nav .nav-link");

                            for (let i = 0; i < btns.length; i++) {
                                btns[i].addEventListener("click",
                                    function() {
                                        const current = document
                                            .getElementsByClassName("active");

                                        current[0].className = current[0]
                                            .className.replace(" active", "");

                                        this.className += " active";
                                    });
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-md-2 m-1">
                <div class="card justify-content-center mt-2 mb-2">

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #00BCF9">
                        <p>Total Members</p>
                    </div>

                    <div class="pt-2 pb-2">
                        <div class="bg-white rounded-lg pb-2 pt-2">
                            <!-- Progress bar 1 -->
                            <div class="progress mx-auto" data-value='100'>
                                <span class="progress-left">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold" style="color: #95CD58">130</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h4 class="text-center p-2"> </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 m-1">
                <div class="card justify-content-center mt-2 mb-2">

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #95CD58">
                        <p>Total Retailers</p>
                    </div>

                    <div class="pt-2 pb-2">
                        <div class="bg-white rounded-lg pb-2 pt-2">
                            <!-- Progress bar 1 -->
                            <div class="progress mx-auto" data-value='100'>
                                <span class="progress-left">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold" style="color: #00BCF9">75</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h4 class="text-center p-2"> </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 m-1">
                <div class="card justify-content-center mt-2 mb-2">

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #E58821">
                        <p>Total VIP Members</p>
                    </div>

                    <div class="pt-2 pb-2">
                        <div class="bg-white rounded-lg pb-2 pt-2">
                            <!-- Progress bar 1 -->
                            <div class="progress mx-auto" data-value='100'>
                                <span class="progress-left">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold" style="color: #E58821">45</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h4 class="text-center p-2"> </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @yield('content')


    <footer class="mt-5">
        <div style="background-color: #E58821; height: 50px; width: 100%">

        </div>
        <div class="container-fluid">
            <div class="row" style="background-color: #3C9282">
                <div class="col-md-4">
                    <img src="{{asset('images/logo.png')}}" alt="logo" width="30%" style="margin: 2%">
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
</body>

</html>