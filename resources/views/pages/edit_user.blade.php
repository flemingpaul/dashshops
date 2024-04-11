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
                                    <h2 class="font-weight-bold mt-4 text-center">Update Profile</h2>
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
                                    
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
