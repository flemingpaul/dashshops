@php use App\Models\Category; @endphp
@extends('layout')



@section('content')

    <main class="login-form bg-white">

        <div class="container">

            <!-- Sign up Form -->
            <div class="row mt-5 justify-content-center" id="reg-form">
                <div class="col-md-5 mr-1 mt-4 col-sm-12 ">
                    <img src="{{asset('landing.png')}}" alt="laptop" width="100%"/>
                    <div>
                        <h4 style="font-size: 30px; text-align: center; font-weight: bolder;">
                            <span style="color: #3B9282">The DashShop</span><br /> Promoting your business effectively
                        </h4><br />
                       <div> </div>

                     <!--   <p class="text-black-50">
                            Reach new customers, build loyalty, become a true destination. Get started today with $0 upfront costs!
                        </p> -->
                        <ul style="list-style-image: url(/checkmark.png)">
                            <li class="mt-3"><strong>Cultivate Your Online Brand with The DashShop.</strong> Where Merchants Excel and Shoppers Save. </li>
                            <li class="mt-3"><strong>Elevate Your Brand with The DashShop.</strong> Where Marketing Expertise Meets Mobile App Excellence.</li>
                            <li class="mt-3"><strong>The DashShop, Your Path to Prosperity.</strong> Drive Foot Traffic and Attract Customers with the The DashShop App Mastery.</li>
                        </ul>

                    </div>
                </div>
                <div class="card col-md-6 mt-4 ml-1 col-sm-12">

                    <h4 class="text-black-50 mt-3">
                      Get Started Today with $0 Upfront Costs !
                    </h4>
                    <form action="{{ route('register.post') }}" method="POST">

                        @csrf

                        <div class="form-group mt-4 row">

                            <label for="name"
                                   class="col-md-12 col-sm-12 font-weight-bold col-form-label">Business
                                Name</label>
                            <div class="col-md-12">

                                <input type="text" id="name" class="form-control" name="business_name"
                                       placeholder="Business Name" required
                                       autofocus>

                                @if ($errors->has('name'))

                                    <span class="text-danger">{{ $errors->first('name') }}</span>

                                @endif

                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="address"
                                   class="col-md-12 col-sm-12 font-weight-bold col-form-label">Business Address</label>
                            <div class="col-md-12">

                                <input type="text" id="address" class="form-control" name="business_address"
                                       placeholder="Business Address" required
                                       autofocus>

                                @if ($errors->has('address'))

                                    <span class="text-danger">{{ $errors->first('address') }}</span>

                                @endif

                            </div>

                        </div>


                        <div class="form-group row">

                            <div class="col-md-6">
                                <label for="firstname"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">First Name</label>

                                <input type="text" id="firstname" class="form-control" name="firstname"
                                       placeholder="First Name" required
                                       autofocus>

                                @if ($errors->has('firstname'))

                                    <span class="text-danger">{{ $errors->first('lastname') }}</span>

                                @endif

                            </div>
                            <div class="col-md-6">

                                <label for="lastname"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">Last
                                    Name</label>
                                <input type="text" id="lastname" class="form-control" name="lastname"
                                       placeholder="Last Name" required
                                       autofocus>

                                @if ($errors->has('lastname'))

                                    <span class="text-danger">{{ $errors->first('lastname') }}</span>

                                @endif

                            </div>

                        </div>
                        <div class="form-group row">

                            <div class="col-md-6">
                                <label for="email_address"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">Email Address</label>

                                <input type="email" id="email_address" placeholder="Email Address" class="form-control"
                                       name="email" required
                                       autofocus>

                                @if ($errors->has('email'))

                                    <span class="text-danger">{{ $errors->first('email') }}</span>

                                @endif

                            </div>
                            <div class="col-md-6">
                                <label for="phone_number"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">Phone
                                    Number</label>

                                <input type="text" id="phone_number" class="form-control" name="phone_number"
                                       placeholder="Phone Number" required
                                       autofocus>

                                @if ($errors->has('phone_number'))

                                    <span class="text-danger">{{ $errors->first('phone_number') }}</span>

                                @endif

                            </div>

                        </div>

                        <div class="form-group mt-2 mb-4 row">

                            <label for="business_category"
                                   class="col-md-12 col-form-label">Type of Business:</label>

                            <div class="col-md-12">

                                <select name="type_of_business" class="form-control"
                                        id="business_category">
                                    <option value="0">Select Category</option>
                                    @forelse ( Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @empty
                                        <option value=""></option>
                                    @endforelse
                                </select>
                                @if ($errors->has('type_of_business'))

                                    <span
                                        class="text-danger">{{ $errors->first('type_of_business') }}</span>

                                @endif

                            </div>

                        </div>


                        <div class="form-group row">

                            <div class="col-md-6">
                                <label for="password"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password"
                                       placeholder="Password" required>
                                <span onclick="myFunction()"
                                      class="fa fa-fw fa-eye field-icon toggle-password"></span>

                                <script>
                                    function myFunction() {
                                        const x = document.getElementById("password");
                                        if (x.type === "password") {
                                            x.type = "text";
                                        } else {
                                            x.type = "password";
                                        }
                                    }
                                </script>
                                @if ($errors->has('password'))

                                    <span class="text-danger">{{ $errors->first('password') }}</span>

                                @endif

                            </div>
                            <div class="col-md-6">
                                <label for="confirm-password"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">Confirm Password</label>

                                <input id="confirm-password" type="password" class="form-control" name="confirm_password"
                                       required placeholder="Re-Enter Password">
                                <span toggle="#confirm-password"
                                      class="fa fa-fw fa-eye field-icon toggle-confirm-password"></span>

                                <script>
                                    $(".toggle-confirm-password").click(function () {

                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                        const input = $($(this).attr("toggle"));
                                        if (input.type === "password") {
                                            input.type = "text";
                                        } else {
                                            input.type = "password";
                                        }
                                    });
                                </script>


                                @if ($errors->has('confirm_password'))

                                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>

                                @endif

                            </div>

                        </div>


                        <div class="form-group form-check row">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">  By clicking below, I agree to the terms
                                and Conditions</label>
                        </div>


                        <div class="col-md-12">

                            <button type="submit" class="btn form-control"
                                    style="background-color: #E89342; color: #ffffff">

                                Sign Up Today

                            </button>

                        </div>

                    </form>
                    <p class="text-center text-black-50 mt-3">No Credit Card Required</p>

                    <p class="text-center" style="font-size: 20px">Already have an account<a href="{{ route('login') }}" style="color: #E89342; margin-left: 10px">Sign In</a></p>
                </div>

            </div>
        </div>
        <div class="container-fluid" style="background-color: #FEFBF2">
            <div class="container">
                <div class="row mt-5 mb-5">
                    <div class="col-md-6 col-sm-12">
                        <video width="100%" height="400" controls autoplay>
                            <source src="/video.mov" type="video/mp4">
                            <source src="/808Welcome.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="col-md-6 col-sm-12 text-left align-content-center justify-content-center">
                        <div class="d-flex flex-row" style="margin-top: 10%">
                            <img src="/video-arrow.png" alt="arrow" width="100" height="50" style="position: relative; margin-top: 25%">
                            <h3 class="text-center" style="width: 350px; margin-top: 15%; margin-left: -05%;  margin-right: 15%; font-weight: bolder"><strong>Capture additional consumers through strategic marketing and search engine optimization</strong></h3>
                        </div>
                        <p class="text-black-50 mt-3 text-center font-weight-bold"
                           style="width: 380px; font-size: 14px; margin-left: 15%;">
                            Are you a Retailer lacking exposure with quality products?<br />Don't let them go unnoticed anymore!<br /><br />The DashShop will ensure your business is advertised properly so both visitors and locals walk-through your door.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="background-color: #FFFFFF">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-12" style="margin-top: 10%">
                        <div class="d-flex flex-row p-3" style="background-color: #F8F8F8">
                            <img src="{{asset('images/rays.png')}}" alt="image" width="60" height=60" style="margin-top: 10%"/>
                            <div class="ml-3">
                                <h5 class="font-weight-bold">Expand Your Customer Base</h5>
                                <p class="mt-4 text-black-50 font-weight-bold">
                                    Search engine optimization ensures your business advertisement gets utilized in the most productive manner. The more eyes that see your advertisement, the more foot traffic through your establishment.
                                </p>
                            </div>
                        </div>
                       <div class="d-flex flex-row mt-3 p-3" style="background-color: #F8F8F8">
                            <img src="{{asset('images/head.png')}}" alt="image" width="60" height=60"
                                 style="margin-top: 10%"/>
                            <div class="ml-3">
                                <h5 class="font-weight-bold">Exposure and Financial growth</h5>
                                <p class="mt-4 text-black-50 font-weight-bold">
                                    With millions of people visiting Maui every year, billions of dollars are spent here on an annual basis. The DashShop provides a marketing solution that will help you capture more of the market share that you as a local business owner, deserve.
                                </p>
                            </div>
                        </div> 
                        <div class="d-flex flex-row mt-3 p-3" style="background-color: #F8F8F8">
                            <img src="{{asset('images/pin.png')}}" alt="image" width="60" height=60" style="margin-top: 05%"/>
                            <div class="ml-3">
                                <h5 class="font-weight-bold">Upon arrival to Maui, The DashShop advertises eye catching QR codes, inviting vacationers to download te app for free.</h5>
                                <p class="mt-4 text-black-50 font-weight-bold">
                                    With location services enable, vacationers will be able to see your promotion when they enter the vicinity of your business.<br /><br />If the consumer, declines enabling location services, they will still be receiving push notification for prioritized businesses based upon the category of business and the differing packages The DashShop offers.<br /><br />Please see your local The DashShop representative for details pertaining to package benefits and pricing.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12" style="background-color: #F8F8F8; margin-top: 10%;">
                        <h5 class="font-weight-bold mt-6 text-center" style="color: #E89342; margin-top: 10%:"><br />Benefits for Merchants</h5>

                        <img src="{{asset('images/man.png')}}" alt="laptop" width="80%" style="border-radius: 10px; margin-left: 10%; margin-right: 10%; background-color: #eff8f7"/>

                        <h4 class="text-center font-weight-bold mt-4" style="margin-left: 10%; width 05%">
                        Provide your consumer with customizable discounts at your discretion.</h4>
                        <p class="text-black-50 font-weight-bold  text-justify mt-5" style="width; 90%; margin-left: 5%">
                          By assigning a limit time offer to your product(s) of choice, The DashShop will send appealing 
                          push notifications to consumers in your area of the deal, you're currently advertising.  
                        </p>
                    </div>
                </div>
            </div>
        </div>
            <!--The DashShop -->
            <div class="container-fluid" style="background-color: #3B9282; height: 300px; border-radius: 10px">
                <div class="container">
                    <div
                        class="row justify-content-center"
                        style="margin-top: 10%"
                    >
                        <div class="col-md-6 col-sm-10">
                            <img src="{{asset('working.png')}}" alt="barista" height="400" style="margin-top: -50px; border-radius: 10px"/>
                        </div>
                        <div class="col-md-6 justify-content-center">
                            <h3 class="font-weight-bold text-white ml-3" style="margin-top: 10%">
                                Additional Benefits
                            </h3>
                            <p class="ml-3 text-white text-left">
                                The DashShop provides real time analysis and analytics for our retailers that ensure that our marketing services are providing the financial growth your business needs.<br />
                               An array of other services are also provided, please talk to your local representative about the differing packages available for retailers.<br /><br />Together, we can determine what is best for you and your  business personally.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        <div class="container mb-4">
            <div class="row justify-content-center" style="margin-top: 10%">
                <div class="col-5 col-md-5 ml-1 col-sm-12 align-content-center justify-content-center" style="background-color: #E89342; border-radius: 15px">
                    <h4 class="font-weight-bold text-white text-center"
                        style="text-align: center; margin-top: 10%">
                        Are you tired of not being able to allocate the proper amount of funding to advertising?
                    </h4><br />
                    <p class="mt-5 text-white text-center">
                        Are you tired of big corporations and franchises taking your marketing share and consumers because their advertising budget supersedes what any local business owner is able to afford?
                    </p>
                    <p class="text-white mt-5"
                        style="text-align: center;">
                       If so, The DashShop is the answer for your advertising needs and within the budget range that nearly every local business owner can afford. Don't miss your opportunity to get in at the beginning of our push to take back the profits your establishment is at risk of losing due to the corporation persisting encroachment of local business owners here that continue to struggle during this difficult time.</p>
                    <p class="text-white mt-5" With differing packages available, your business' financial risk vs. reward potential significantly favors you and your business. You're investing in an app built by local business owners intented to help other local business owners.
		    </p>
                    <p class="text-white mt-5" We personally lost businesses during the Lahaina wildfire and we know without a marketing provision at a budget cost, we are all at risk of being overtaken by the financial interests that see anopportunity here on the island white everyone is struggling. Our motivation is to fiancially benefit your establishment and relieve some of the stress we've all endured from years now of record low numbers.
                    </p>
                    <p class="text-white mt-5" Join The DashShop today. What do you have to lose?
	           </p> 
                        <a class="btn btn-light text-center mt-3" href="#reg-form" style="margin-left: 30%; border-radius: 20px">
                        Get Started
                    </a>
                </div>
                <div class="col-6 col-md-6 col-sm-12" style="margin-left: -10px">
                    <img src="{{asset('images/new-barista.png')}}" alt="barista" width="100%" height="500px" style="border-radius: 15px" />
                </div>
            </div>
        </div>

    </main>

@endsection
