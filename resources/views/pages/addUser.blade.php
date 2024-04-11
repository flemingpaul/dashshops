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
                <div class="col-md-12 col-sm-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{asset('images/logos.png')}}" alt="logo" width="70%">
                                </div>
                                <div class="col-md-7">
                                    <h2 class="font-weight-bold mt-5 text-center">Add User</h2>
                                    @if (session('message'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('message') }}
                                        </div>
                                    @endif
                                    <div class="row justify-content-center align-items-center">


                                        <div class="col-md-12">
                                            <form action=" {!! route('user.create') !!}" method="POST">

                                                @csrf

                                                <div class="form-group row">

                                                    <label for="firstname"
                                                           class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Firstname</label>

                                                    <div class="col-md-12">

                                                        <input type="text" id="firstname" class="form-control"
                                                               name="firstname"
                                                               style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;"
                                                               required autofocus value="{{old('firstname')}}">

                                                        @if ($errors->has('firstname'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('firstname') }}</span>

                                                        @endif

                                                    </div>

                                                </div>


                                                <div class="form-group row">

                                                    <label for="lastname"
                                                           class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">
                                                        Lastname
                                                    </label>

                                                    <div class="col-md-12">

                                                        <input type="text" id="lastname"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               class="form-control" name="lastname" required value="{{old('lastname')}}">

                                                        @if ($errors->has('lastname'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('lastname') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="phone_number" class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Phone
                                                        Number</label>

                                                    <div class="col-md-12">

                                                        <input type="tel" id="phone_number" class="form-control"
                                                               name="phone_number"
                                                               style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;"
                                                               required value="{{old('phone_number')}}">

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
                                                               name="address"
                                                               style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;"
                                                               required value="{{old('address')}}">

                                                        @if ($errors->has('address'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('address') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <div class="col-md-6">

                                                    <label for="email" class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Email</label>
                                                        <input type="email" id="email" class="form-control"
                                                               style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;"
                                                               name="email" required value="{{old('email')}}">

                                                        @if ($errors->has('email'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('email') }}</span>

                                                        @endif

                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="admin" style="font-size: 18px" class="col-md-6 col-form-label font-weight-bold">User Type</label>
                                                        <select name="admin" class="form-control"
                                                                id="admin"
                                                                style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;" required>
                                                            <option value="1" >Admin User</option>
                                                            <option value="2" selected >Sales User</option>
                                                            
                                                        </select>
                                                        

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="city" class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">City</label>

                                                    <div class="col-md-12">

                                                        <input type="text" id="city" class="form-control"
                                                               style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;"
                                                               name="city" required value="{{old('city')}}">

                                                        @if ($errors->has('city'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('city') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <div class="col-md-6">
                                                        <label for="state" style="font-size: 18px" class="col-md-6 col-form-label font-weight-bold">State</label>
                                                        <select name="state" class="form-control"
                                                                id="state"
                                                                style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;" required>
                                                            <option value="">Select State</option>
                                                            @foreach ($states as $state)
                                                                <option
                                                                    value="{{ $state->name }}">{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('state'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('state') }}</span>

                                                        @endif

                                                    </div>
                                                    <div class="col-md-6">

                                                        <label for="zip_code" style="font-size: 18px" class="col-md-6 col-form-label font-weight-bold">Zip
                                                            Code</label>

                                                        <input type="text" id="zip_code" class="form-control"
                                                               name="zip_code" value="{{old('zip_code')}}"
                                                               required
                                                               style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;"
                                                               autofocus>

                                                        @if ($errors->has('zip_code'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('zip_code') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="password"
                                                           class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Password</label>

                                                    <div class="col-md-12">

                                                        <input type="password" id="password"
                                                               style="border-color: #FFFFFF; box-shadow: 5px 4px #efefef;"
                                                               class="form-control" name="password" value="{{old('password')}}" required>

                                                        @if ($errors->has('password'))

                                                            <span class="text-danger">{{ $errors->first('password') }}</span>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div class="form-group row">

                                                    <label for="confirm-pass" class="col-md-12 col-form-label font-weight-bold" style="font-size: 18px">Confirm
                                                        Password</label>

                                                    <div class="col-md-12">

                                                        <input type="password"
                                                               style="border-color: #FFFFFF;   box-shadow: 5px 4px #efefef;"
                                                               id="confirm-pass" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}"
                                                               required>

                                                        @if ($errors->has('password_confirmation'))

                                                            <span
                                                                class="text-danger">{{ $errors->first('password_confirmation') }}</span>

                                                        @endif

                                                    </div>

                                                </div>


                                                <div class="col-md-6 offset-md-4">

                                                    <button type="submit" class="btn p-3 text-white"
                                                            style="background-color: #E58821; width: 150px; margin-top: 10%; height: 50px">

                                                        Save

                                                    </button>

                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 justify-content-end">
                                    <img src="{{asset('images/admin.png')}}" alt="logo" width="40%" style="margin-top: 6%; margin-left: 45%">
                                    <a class="btn btn-primary justify-end" style="border-radius: 15px; width: 100px; margin-left: 45%">Edit
                                    </a>
                                    <div class="mt-3">
                                        <div class="col-md-12">
                                            <label for="password" class="font-weight-bold text-center ml-4 col-md-12"
                                                   style="font-size: 11px">Current Password</label>
                                            <input id="password" type="password" class="form-control" name="password"
                                                   placeholder="Password" required style="width: 150px; border-radius: 15px; margin-left: 30%">
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
                                        <div class="col-md-12 mt-2">
                                            <label for="change_password" class="font-weight-bold ml-4 text-center col-md-12"
                                                   style="font-size: 11px">Change Password</label>
                                            <input id="change_password" type="password" class="form-control"
                                                   name="password" placeholder="Password" required style="width: 150px; border-radius: 15px; margin-left: 30%">
                                            <span toggle="#password-field"
                                                  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            @if ($errors->has('password'))

                                                <span class="text-danger">{{ $errors->first('password') }}</span>

                                            @endif

                                        </div>
                                        <div class="col-md-8 mt-4 ml-4">
                                            <button type="submit" class="btn p-2 btn-primary form-control" style="margin-left: 40%; width: 130px">
                                                Save
                                            </button>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <p class="font-weight-bold mt-5 col-md-12 text-right ml-3">Update billing Info</p>
                                            <a href="#" class="btn btn-primary col-md-8 form-control" style="margin-left: 40%; margin-top: -10px; width: 130px">
                                                Update
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="font-weight-bold mt-4 col-md-12 text-right">Billing Details</p>
                                            <a href="#" class="btn p-2 col-md-8 btn-primary form-control" style="margin-left: 40%; margin-top: -10px; width: 130px">
                                                Update
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="font-weight-bold mt-4 col-md-12 text-center ml-5">Add User</p>
                                            <a href="{{ route('add-user') }}"
                                               class="btn col-md-8 p-2 btn-primary form-control" style="margin-left: 40%; margin-top: -10px; width: 130px">
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
