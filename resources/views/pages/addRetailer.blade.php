@extends('dashboard')

@section('extra_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<main>

    <div class="container-fluid" style="background-color: #efefef">
        <div class="row ml-5">
            <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                <div class="card justify-content-center" style="min-width: 150px; width: 250px">
                    <div class="card-body">
                        @include('partials.menu',['active'=>'add-retailer'])
                        <script type="text/javascript">
                            $(document).on('click', '.nav-pills li', function() {
                                $(".nav-pills li").removeClass("active");
                                $(this).addClass("active");
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 m-1">
                <div class="card justify-content-center mt-2 mb-2">

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #7AB91D">
                        <p style="font-size: 12px">Coupon Clicks</p>
                    </div>

                    <div class="card-body">
                        <img src="{{asset('images/clicks.png')}}" alt="downloads" width="90%" />
                    </div>
                    <div class="card-footer">
                        <h4 class="text-center" style="color: #7AB91D">{{ $total_clicks }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-3 m-1">
                <div class="card justify-content-center mt-2 mb-2">

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #00B7F9">
                        <p style="font-size: 12px">Coupon Downloads</p>
                    </div>

                    <div class="card-body">
                        <img src="{{asset('images/downloads.png')}}" alt="downloads" width="90%" />
                    </div>
                    <div class="card-footer">
                        <h4 class="text-center" style="color: #00B7F9">{{ $total_downloads }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2 m-1">
                <div class="card justify-content-center mt-2 mb-2">

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #E58821">
                        <p style="font-size: 12px">Coupon Redeemed</p>
                    </div>

                    <div class="card-body">
                        <img src="{{asset('images/redeemed.png')}}" alt="downloads" width="90%" />
                    </div>
                    <div class="card-footer">
                        <h4 class="text-center" style="color: #E58821">{{ $total_redemptions }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 5%">
        <div class="row justify-content-center align-items-center">

            <div class="col-md-12">

                <div class="card">
                    <div class="card-body justify-content-center">
                        <h5 class="card-title ">Add Retailer</h5>
                        @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                        @endif
                        <form action=" {!! route('add-retailer') !!}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center align-items-center" style="margin-left: 5%; margin-right: 5%">



                                <div class="col-md-11" style=" padding: 30px; margin-top: 5%">
                                    <div class="row">
                                        <div class="col-3">
                                            <div id="imgBanner" class="text-center" style="display: none">
                                            </div>
                                            <div class="form-group">
                                                <label for="banner_image">Banner Image</label>
                                                <input type="file" class="form-control-file" id="banner_image" name="banner_image" accept="image/*" required>
                                                @if ($errors->has('banner_image'))
                                                <small class="text-danger">{{ $errors->first('banner_image')}}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="row">
                                                <h5 class="col-12">Account Information</h5>
                                                <hr class="col-12" />
                                                <div class="form-group col-6 mb-4">
                                                    <label>Business Name</label>
                                                    <input type="text" class="form-control" placeholder="Business Name" name="business_name" value="{{ old('business_name') }}" required autofocus>
                                                    @if ($errors->has('business_name'))
                                                    <small class="text-danger">{{ $errors->first('business_name')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-6 mb-4">
                                                    <label>Retailer's Username</label>
                                                    <input type="text" class="form-control" placeholder="Retailer's Username" name="username" value="{{ old('username') }}" required>
                                                    @if ($errors->has('username'))
                                                    <small class="text-danger">{{ $errors->first('username')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-6 mb-4">
                                                    <label>Retailer's Firstname</label>
                                                    <input type="text" class="form-control" placeholder="Retailer's Firstname" name="firstname" value="{{ old('firstname') }}" required>
                                                    @if ($errors->has('firstname'))
                                                    <small class="text-danger">{{ $errors->first('firstname')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-6 mb-4">
                                                    <label>Retailer's lastname</label>
                                                    <input type="text" class="form-control" placeholder="Retailer's lastname" name="lastname" value="{{ old('lastname') }}" required>
                                                    @if ($errors->has('lastname'))
                                                    <small class="text-danger">{{ $errors->first('lastname')}}</small>
                                                    @endif
                                                </div>

                                                <div class="form-group col-6 mb-4">
                                                    <label>Email Address</label>
                                                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required>
                                                    @if ($errors->has('email'))
                                                    <small class="text-danger">{{ $errors->first('email')}}</small>
                                                    @endif
                                                </div>

                                                <div class="form-group col-6 mb-4">
                                                    <label>Password</label>
                                                    <input type="password" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}" required>
                                                    @if ($errors->has('password'))
                                                    <small class="text-danger">{{ $errors->first('password')}}</small>
                                                    @endif
                                                </div>
                                                <h5 class="col-12">Store Information</h5>
                                                <hr class="col-12" />

                                                <div class="form-group col-12 mb-4">
                                                    <label>Business Description (190 Characters)</label>
                                                    <textarea type="text" class="form-control" maxlength="190" placeholder="Business Description" name="business_description" required>{{ old('business_description') }}</textarea>
                                                    @if ($errors->has('business_description'))
                                                    <small class="text-danger">{{ $errors->first('business_description')}}</small>
                                                    @endif
                                                </div>

                                                <div class="form-group col-6 mb-4">
                                                    <label>Business Days</label>
                                                    <select name="open_day" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="open_day">
                                                        <option value="">Days</option>
                                                        <option value="Monday-Friday">Monday-Friday</option>
                                                        <option value="Saturday-Sunday">Saturday-Sunday</option>

                                                    </select>
                                                    @if ($errors->has('open_day'))
                                                    <small class="text-danger">{{ $errors->first('open_day')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-6 mb-4">
                                                    <label>Business Hour</label>
                                                    <select name="open_time" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="open_time">
                                                        <option value="">Hour</option>
                                                        <option value="9am-6pm">9am-6pm</option>
                                                        <option value="11am-11pm">11am-11pm</option>
                                                        <option value="Various">Various</option>
                                                    </select>
                                                    @if ($errors->has('open_time'))
                                                    <small class="text-danger">{{ $errors->first('open_time')}}</small>
                                                    @endif
                                                </div>

                                                <div class="form-group col-6 mb-4">
                                                    <label>Closed Business Days</label>
                                                    <select name="closed_day" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="closed_day">
                                                        <option value="">Days</option>
                                                        <option value="Monday-Friday">Monday-Friday</option>
                                                        <option value="Saturday-Sunday">Saturday-Sunday</option>

                                                    </select>
                                                    @if ($errors->has('closed_day'))
                                                    <small class="text-danger">{{ $errors->first('closed_day')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-6 mb-4">
                                                    <label>Closed Business Hour</label>
                                                    <select name="closed_time" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="closed_time">
                                                        <option value="">Hour</option>
                                                        <option value="9am-6pm">9am-6pm</option>
                                                        <option value="11am-11pm">11am-11pm</option>
                                                        <option value="Various">Various</option>
                                                    </select>
                                                    @if ($errors->has('closed_time'))
                                                    <small class="text-danger">{{ $errors->first('closed_time')}}</small>
                                                    @endif
                                                </div>



                                                <div class="form-group col-6 mb-4">
                                                    <label>Type of Business</label>
                                                    <select name="type_of_business" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="business_category">
                                                        <option value=""></option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('type_of_business'))
                                                    <small class="text-danger">{{ $errors->first('type_of_business')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-7 mb-4">
                                                    <label>Website</label>
                                                    <input type="url" class="form-control" placeholder="Website" name="web_url" value="{{ old('web_url') }}" required>
                                                    @if ($errors->has('web_url'))
                                                    <small class="text-danger">{{ $errors->first('web_url')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-5 mb-4">
                                                    <label>Phone Number</label>
                                                    <input type="tel" class="form-control" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number') }}" required>
                                                    @if ($errors->has('phone_number'))
                                                    <small class="text-danger">{{ $errors->first('phone_number')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-8 mb-4">
                                                    <label>Retailer's Address</label>
                                                    <input type="text" class="form-control" placeholder="Retailer's Address" name="business_address" value="{{ old('business_address') }}" required>
                                                    @if ($errors->has('business_address'))
                                                    <small class="text-danger">{{ $errors->first('business_address')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-4 mb-4">
                                                    <label>Zip Code</label>
                                                    <input type="number" class="form-control" placeholder="Zip Code" name="zip_code" value="{{ old('zip_code') }}" required>
                                                    @if ($errors->has('zip_code'))
                                                    <small class="text-danger">{{ $errors->first('zip_code')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-4 mb-4">
                                                    <label>Island</label>
                                                    <select name="island" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="island" onchange="islandChanged()">
                                                        <option value="">Select Island</option>
                                                        <option value="oahu">Oahu</option>
                                                        <option value="maui">Maui</option>
                                                        <option value="kauai">Kauai</option>
                                                        <option value="hawaii">Hawaii</option>
                                                    </select>
                                                    @if ($errors->has('island'))
                                                    <small class="text-danger">{{ $errors->first('island')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-4 mb-4">
                                                    <label>City</label>
                                                    <div id="divCity">
                                                        <select name="city" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="city">
                                                            <option value="">Select City</option>

                                                        </select>
                                                    </div>
                                                    @if ($errors->has('city'))
                                                    <small class="text-danger">{{ $errors->first('city')}}</small>
                                                    @endif
                                                </div>
                                                <div class="form-group col-4 mb-4">
                                                    <label>State</label>

                                                    <select name="state" class="form-control select2" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" id="state">
                                                        <option value="Hawaii" selected>Hawaii</option>

                                                    </select>

                                                    @if ($errors->has('state'))
                                                    <small class="text-danger">{{ $errors->first('state')}}</small>
                                                    @endif
                                                </div>


                                                <div class="col-md-5 offset-md-4">

                                                    <button type="submit" class="btn btn-primary pb-4 form-control text-white" style="margin-top: 15%; margin-bottom: 15%">

                                                        Add Retailer

                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('extra_js')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('js/addretailer.js')}}"></script>
<script>
    $(document).ready(function() {
        //new DataTable('#table_created_retailers');
        $('.select2').select2();
        $("#banner_image").change(function() {
        if ($("#banner_image").val() === "") {

        } else {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.addEventListener("load", function () {
                    $("#imgBanner").html(
                        '<img src="' + reader.result + '" class="img-fluid" alt="">'
                    );
                    $("#imgBanner").css("display", "block");
        
                }, false);
                reader.readAsDataURL(this.files[0]);
        
        
            }
        }
    });

    })
</script>

@endsection