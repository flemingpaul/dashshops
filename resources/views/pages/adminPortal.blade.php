@extends('dashboard')

@section('content')
    <main>
        <div class="container-fluid" style="background-color: #efefef">
            <div class="row ml-5">
                <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                    <div class="card justify-content-center" style="width: 250px">
                        <div class="card-body">
                        @include('partials.menu',['active'=>'users'])
                            <script>

                                /* Code for changing active
                                link on clicking */
                                const btns =
                                    $("#navigation .navbar-nav .nav-link");

                                for (let i = 0; i < btns.length; i++) {
                                    btns[i].addEventListener("click",
                                        function () {
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
                            <p style="font-size: 12px">Total Members</p>
                        </div>

                        <div class="pt-2 pb-2">
                            <div class="bg-white rounded-lg pb-2 pt-2">
                                <!-- Progress bar 1 -->
                                <div class="progress mx-auto" data-value='100'>
                                              <span class="progress-left">
                                                            <span class="progress-bar"
                                                                  style="border-color: #000000; border-width: 10px"></span>
                                              </span>
                                    <span class="progress-right">
                                                            <span class="progress-bar"
                                                                  style="border-color: #000000; border-width: 10px"></span>
                                              </span>
                                    <div
                                        class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                        <div class="h2 font-weight-bold" style="color: #95CD58">{{ $total_members }}</div>
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
                            <p style="font-size: 12px">Total Retailers</p>
                        </div>

                        <div class="pt-2 pb-2">
                            <div class="bg-white rounded-lg pb-2 pt-2">
                                <!-- Progress bar 1 -->
                                <div class="progress mx-auto" data-value='100'>
                                              <span class="progress-left">
                                                            <span class="progress-bar"
                                                                  style="border-color: #000000; border-width: 10px"></span>
                                              </span>
                                    <span class="progress-right">
                                                            <span class="progress-bar"
                                                                  style="border-color: #000000; border-width: 10px"></span>
                                              </span>
                                    <div
                                        class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                        <div class="h2 font-weight-bold" style="color: #00BCF9">{{ $total_retailers }}</div>
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
                            <p style="font-size: 12px">Total VIP Members</p>
                        </div>

                        <div class="pt-2 pb-2">
                            <div class="bg-white rounded-lg pb-2 pt-2">
                                <!-- Progress bar 1 -->
                                <div class="progress mx-auto" data-value='100'>
                                              <span class="progress-left">
                                                            <span class="progress-bar"
                                                                  style="border-color: #000000; border-width: 10px"></span>
                                              </span>
                                    <span class="progress-right">
                                                            <span class="progress-bar"
                                                                  style="border-color: #000000; border-width: 10px"></span>
                                              </span>
                                    <div
                                        class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                        <div class="h2 font-weight-bold" style="color: #E58821">{{ $total_vip }}</div>
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
        <div class="container" style="margin-top: 5%">

            <div class="row justify-content-center">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 col-sm-2">
                                    <img src="{{asset('images/logos.png')}}" alt="logo" width="70%">
                                </div>
                                <div class="col-md-7 col-sm-6">
                                    <h2 class="font-weight-bold mt-4 text-center">Admin Portal System Settings</h2>
                                    <div class="row  m-5 justify-content-center align-items-center">

                                        <div class="col-md-12">
                                            <form action="{{ route('update-user', auth()->user()->id)}}" method="POST">

                                                @csrf

                                                <div class="form-group row">

                                                    <label for="firstname"
                                                           class="col-md-12 font-weight-bold col-form-label" style="font-size: 18px">Firstname</label>

                                                    <div class="col-md-12">

                                                        <input type="text" id="firstname"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               class="form-control" name="firstname" required autofocus value="{{Auth::user()->firstname}}">

                                                        @if ($errors->has('firstname'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('firstname') }}</span>

                                                        @endif

                                                    </div>

                                                </div>


                                                <div class="form-group row">

                                                    <label for="lastname"
                                                           class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Lastname</label>

                                                    <div class="col-md-12">

                                                        <input type="text" id="lastname"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               class="form-control" name="lastname" required value="{{Auth::user()->lastname}}">

                                                        @if ($errors->has('lastname'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('lastname') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="phone_number"
                                                           class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Phone
                                                        Number</label>

                                                    <div class="col-md-12">

                                                        <input type="tel" id="phone_number"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               class="form-control" name="phone_number" required  value="{{Auth::user()->phone_number}}">

                                                        @if ($errors->has('phone_number'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('phone_number') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="address"
                                                           class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Address</label>

                                                    <div class="col-md-12">

                                                        <input type="text" id="address" class="form-control"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               name="business_address" required value="{{Auth::user()->business_address}}">

                                                        @if ($errors->has('address'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('address') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="city" class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">City</label>

                                                    <div class="col-md-12">

                                                        <input type="text" id="city" class="form-control"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               name="city" required value="{{Auth::user()->city}}">

                                                        @if ($errors->has('city'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('city') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <div class="col-md-6">
                                                        <label for="state"
                                                               class="col-md-12 font-weight-bold col-form-label" style="font-size: 18px">State</label>

                                                        <select autocomplete="off" name="state" class="form-control"
                                                                id="state"
                                                                style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;">
                                                            <option value="">Select State</option>
                                                            @foreach ($states as $state)
                                                                <option
                                                                    value="{{ $state->name }}" @if(Auth::user()->state == $state->name) selected="selected" @endif>{{ $state->name }}, {{ $state->abbreviation }}</option>
                                                            @endforeach
                                                        </select>

                                                        @if ($errors->has('state'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('state') }}</span>

                                                        @endif

                                                    </div>
                                                    <div class="col-md-6">

                                                        <label for="zip_code"
                                                               class="col-md-12 col-form-label font-weight-bold" style="font-size: 16px">Zip
                                                            Code</label>

                                                        <input type="text" id="zip_code"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               class="form-control" name="zip_code"
                                                               required
                                                               autofocus value="{{Auth::user()->zip_code}}">

                                                        @if ($errors->has('zip_code'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('zip_code') }}</span>

                                                        @endif

                                                    </div>

                                                </div>


                                                <div class="col-md-6" style="margin-left: 15%">

                                                    <button type="submit" class="btn p-3 text-white"
                                                            style="background-color: #E58821; margin-top: 10%; width: 200px">

                                                        Save

                                                    </button>

                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                    <div class="col-md-12 ml-2">
                                        <div class="card justify-content-center mt-5" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;">
                                            <form method="post" action="{{ route('files.store') }}"
                                                  style="border-color: #FFFFFF!important; height: 70px"
                                                  enctype="multipart/form-data"
                                                  class="dropzone" id="dropzone">
                                                <div class="dz-message" data-dz-message>
                                                    <img src="{{asset('images/upload.png')}}" alt="upload"/>
                                                    <h5 class="mt-2 font-weight-bold">Drag and Drop to Banner Image to upload <br>
                                                        Banner must be minimum 400 pixels
                                                    </h5>
                                                </div>
                                                @csrf
                                            </form>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-lg-12 margin-tb">
                                                <div class="text-center">
                                                    <a class="btn btn-primary p-2" href="{{ route('files.store') }}"
                                                       title="Upload files"> Upload Image
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="justify-content-center mt-5">
                                        <select name="name" class="form-control col-md-12"
                                                id="category_list" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;">
                                            <option value="Select">Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>

                                        @if (session('message'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('message') }}
                                            </div>
                                        @endif


                                        <div class="mt-3" style="margin-left: 15%">
                                            <form action=" {!! route('add-category') !!}" method="POST">
                                                @csrf

                                                <div class="form-group row">

                                                    <label for="name" class="col-md-12 col-form-label">Category
                                                        Name</label>

                                                    <div class="col-md-10">

                                                        <input type="text" id="name" class="form-control" name="name"
                                                               required style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;">

                                                        @if ($errors->has('name'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('name') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="col-md-5 offset-md-3 mt-5 mb-5">

                                                    <button type="submit" class="btn form-control text-white"
                                                            style="background-color: darkorange; margin-top: 10%">

                                                        Add New

                                                    </button>

                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <img src="{{asset('images/admin.png')}}" alt="logo" width="40%"
                                         style="margin-top: 6%; margin-left: 45%">
                                    <a class="btn btn-primary col-sm-8 justify-end"
                                       style="border-radius: 15px; margin-left: 35%">Edit
                                    </a>
                                    <div class="mt-3">
                                        <form method="POST" action="{{ route('change-password'), auth()->user()->id }}">
                                            @csrf
                                            <div class="col-md-12 change col-sm-12">
                                                <label for="current_password" class="font-weight-bold text-center ml-4 col-md-12 col-sm-12"
                                                       style="font-size: 11px">Current Password</label>
                                                <input id="current_password" type="password" class="form-control col-sm-9" name="current_password"
                                                       placeholder="Password" required
                                                       style="border-radius: 15px; margin-left: 30%">
                                                <span toggle="#password-field"
                                                      class="fa fa-fw fa-eye field-icon toggle-password"></span>

                                                <script>
                                                    $(".toggle-password").click(function () {

                                                        $(this).toggleClass("fa-eye fa-eye-slash");
                                                        var input = $($(this).attr("toggle"));
                                                        if (input.attr("type") === "password") {
                                                            input.attr("type", "text");
                                                        } else {
                                                            input.attr("type", "password");
                                                        }
                                                    });
                                                </script>
                                                @if ($errors->has('password'))

                                                    <span class="text-danger">{{ $errors->first('password') }}</span>

                                                @endif

                                            </div>
                                            <div class="col-md-12 col-sm-12 mt-2">
                                                <label for="change_password"
                                                       class="font-weight-bold ml-4 text-center col-md-12"
                                                       style="font-size: 11px">Change Password</label>
                                                <input id="new_password" type="password" class="form-control col-sm-9"
                                                       name="password" placeholder="Password" required
                                                       style="border-radius: 15px; margin-left: 30%">
                                                <span toggle="#password-field"
                                                      class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                @if ($errors->has('password'))

                                                    <span class="text-danger">{{ $errors->first('password') }}</span>

                                                @endif

                                            </div>
                                            <div class="col-md-8 mt-4 ml-4">
                                                <button type="submit" class="btn p-2 btn-primary col-sm-10 form-control"
                                                        style="margin-left: 40%;">
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                        <div class="col-md-12 mt-4">
                                            <p class="font-weight-bold mt-5 col-sm-12 text-right ml-3">Update billing
                                                Info</p>
                                            <a href="#" class="btn btn-primary col-md-8 form-control"
                                               style="margin-left: 40%; margin-top: -10px; width: 130px">
                                                Update
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="font-weight-bold mt-4 col-md-12 text-right">Billing Details</p>
                                            <a href="#" class="btn p-2 col-md-8 btn-primary form-control"
                                               style="margin-left: 40%; margin-top: -10px; width: 130px">
                                                Update
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="font-weight-bold mt-4 col-md-12 text-center ml-3">Add User</p>
                                            <a href="{{ route('add-user') }}"
                                               class="btn col-md-8 p-2 btn-primary form-control"
                                               style="margin-left: 40%; margin-top: -10px; width: 130px">
                                                Add User
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
