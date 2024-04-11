@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layout')



@section('content')

    <main>
        <div class="container">
            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-md-5 mr-1">
                    <img src="{{asset('images/group.png')}}" alt="laptop" width="100%" style="margin-top: 10%"/>
                    <div>
                        <h4 style="font-size: 25px; color: #212429; font-weight: bolder">
                            Thank you for signing up with The DashShop
                        </h4>
                        <p style="color: #377C6A; font-size: 12px; font-weight: bold">
                            Your merchant profile has approved
                        </p>

                        <p class="text-black-50" style="font-size: 11px">
                            Please complete your retailer profile and join the The DashShop Mobile App.
                            We will notify you when the The DashShop mobile is launched
                        </p>
                    </div>
                </div>
                <div class="card col-md-6 ml-1">
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form
                            action="{{ route('profile-update', auth()->user()->retailer_id) }}"
                            method="POST">

                            @csrf

                            @method('PUT')
                            <div class="row">
                                <div class="col-md-5 mb-2">
                                    <img id="preview-image-before-upload" src="/profile.png"
                                         alt="preview image" style="max-width: 200px;">
                                </div>
                                <div class="col-md-7 mt-5">
                                    <div class="form-group">
                                        <label for="image">Upload a Profile Photo</label>
                                        <input type="file" name="image" placeholder="No file Selected" id="image">
                                        @error('banner_image')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4 row">

                                <label for="city"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">City</label>
                                <div class="col-md-12">

                                    <input type="text" id="city" class="form-control" name="city" value="{{ auth()->user()->city }}"
                                           required autofocus>

                                    @if ($errors->has('city'))

                                        <span class="text-danger">{{ $errors->first('city') }}</span>

                                    @endif

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-md-6">
                                    <label for="state" class="col-md-6 col-form-label font-weight-bold">State</label>
                                    <select name="state" class="form-control"
                                            id="state">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option
                                                value="{{ $state->name }}">{{ $state->name }}
                                               </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('state'))

                                        <span
                                            class="text-danger">{{ $errors->first('state') }}</span>

                                    @endif

                                </div>
                                <div class="col-md-6">

                                    <label for="zip_code" class="col-md-6 col-form-label font-weight-bold">Zip
                                        Code</label>

                                    <input type="text" id="zip_code" value="{{ auth()->user()->zip_code }}" class="form-control"
                                           name="zip_code"
                                           required
                                           autofocus>

                                    @if ($errors->has('zip_code'))

                                        <span
                                            class="text-danger">{{ $errors->first('zip_code') }}</span>

                                    @endif

                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="business_hours" class="col-md-12 col-form-label font-weight-bold">Business
                                    Hours(Monday- Friday)</label>
                                <div class="col-md-6">
                                    <select name="business_hours_open_1" class="form-control"
                                            id="state"
                                    >
                                        <option value="">Select Time</option>
                                        <option value="12am">12am</option>
                                        <option value="1am">1am</option>
                                        <option value="2am">2am</option>
                                        <option value="3am">3am</option>
                                        <option value="4am">4am</option>
                                        <option value="5am">5am</option>
                                        <option value="6am">6am</option>
                                        <option value="7am">7am</option>
                                        <option value="8am">8am</option>
                                        <option value="9am">9am</option>
                                        <option value="10am">10am</option>
                                        <option value="11am">11am</option>
                                        <option value="12pm">12pm</option>
                                        <option value="1pm">1pm</option>
                                        <option value="2pm">2pm</option>
                                        <option value="3pm">3pm</option>
                                        <option value="4pm">4pm</option>
                                        <option value="5pm">5pm</option>
                                        <option value="6pm">6pm</option>
                                        <option value="7pm">7pm</option>
                                        <option value="8pm">8pm</option>
                                        <option value="9pm">9pm</option>
                                        <option value="10pm">10pm</option>
                                        <option value="11pm">11pm</option>
                                    </select>
                                    @if ($errors->has('business_hours_open'))

                                        <span
                                            class="text-danger">{{ $errors->first('business_hours_open') }}</span>

                                    @endif

                                </div>
                                <div class="col-md-6">

                                    <select name="business_hours_close_1" class="form-control"
                                            id="state">
                                        <option value="">Select Time</option>
                                        <option value="12am">12am</option>
                                        <option value="1am">1am</option>
                                        <option value="2am">2am</option>
                                        <option value="3am">3am</option>
                                        <option value="4am">4am</option>
                                        <option value="5am">5am</option>
                                        <option value="6am">6am</option>
                                        <option value="7am">7am</option>
                                        <option value="8am">8am</option>
                                        <option value="9am">9am</option>
                                        <option value="10am">10am</option>
                                        <option value="11am">11am</option>
                                        <option value="12pm">12pm</option>
                                        <option value="1pm">1pm</option>
                                        <option value="2pm">2pm</option>
                                        <option value="3pm">3pm</option>
                                        <option value="4pm">4pm</option>
                                        <option value="5pm">5pm</option>
                                        <option value="6pm">6pm</option>
                                        <option value="7pm">7pm</option>
                                        <option value="8pm">8pm</option>
                                        <option value="9pm">9pm</option>
                                        <option value="10pm">10pm</option>
                                        <option value="11pm">11pm</option>
                                    </select>
                                    @if ($errors->has('state'))

                                        <span
                                            class="text-danger">{{ $errors->first('state') }}</span>

                                    @endif

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="business_hours-end" class="col-md-12 col-form-label font-weight-bold">Business
                                    Hours(Saturday- Sunday)</label>
                                <div class="col-md-6">
                                    <select name="business_hours_open_2" class="form-control"
                                            id="business_hours-end">
                                        <option value="">Select Time</option>
                                        <option value="12am">12am</option>
                                        <option value="1am">1am</option>
                                        <option value="2am">2am</option>
                                        <option value="3am">3am</option>
                                        <option value="4am">4am</option>
                                        <option value="5am">5am</option>
                                        <option value="6am">6am</option>
                                        <option value="7am">7am</option>
                                        <option value="8am">8am</option>
                                        <option value="9am">9am</option>
                                        <option value="10am">10am</option>
                                        <option value="11am">11am</option>
                                        <option value="12pm">12pm</option>
                                        <option value="1pm">1pm</option>
                                        <option value="2pm">2pm</option>
                                        <option value="3pm">3pm</option>
                                        <option value="4pm">4pm</option>
                                        <option value="5pm">5pm</option>
                                        <option value="6pm">6pm</option>
                                        <option value="7pm">7pm</option>
                                        <option value="8pm">8pm</option>
                                        <option value="9pm">9pm</option>
                                        <option value="10pm">10pm</option>
                                        <option value="11pm">11pm</option>
                                    </select>
                                    @if ($errors->has('state'))

                                        <span
                                            class="text-danger">{{ $errors->first('state') }}</span>

                                    @endif

                                </div>
                                <div class="col-md-6">
                                    <select name="business_hours_close_2" class="form-control" id="state">
                                        <option value="">Select Time</option>
                                        <option value="12am">12am</option>
                                        <option value="1am">1am</option>
                                        <option value="2am">2am</option>
                                        <option value="3am">3am</option>
                                        <option value="4am">4am</option>
                                        <option value="5am">5am</option>
                                        <option value="6am">6am</option>
                                        <option value="7am">7am</option>
                                        <option value="8am">8am</option>
                                        <option value="9am">9am</option>
                                        <option value="10am">10am</option>
                                        <option value="11am">11am</option>
                                        <option value="12pm">12pm</option>
                                        <option value="1pm">1pm</option>
                                        <option value="2pm">2pm</option>
                                        <option value="3pm">3pm</option>
                                        <option value="4pm">4pm</option>
                                        <option value="5pm">5pm</option>
                                        <option value="6pm">6pm</option>
                                        <option value="7pm">7pm</option>
                                        <option value="8pm">8pm</option>
                                        <option value="9pm">9pm</option>
                                        <option value="10pm">10pm</option>
                                        <option value="11pm">11pm</option>
                                    </select>
                                    @if ($errors->has('state'))

                                        <span
                                            class="text-danger">{{ $errors->first('state') }}</span>

                                    @endif

                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="web_url"
                                       class="col-md-12 col-sm-12 font-weight-bold col-form-label">Website Url</label>
                                <div class="col-md-12">

                                    <input type="url" id="web_url" class="form-control" name="web_url" value="{{ auth()->user()->web_url }}" required
                                           autofocus>

                                    @if ($errors->has('web_url'))

                                        <span class="text-danger">{{ $errors->first('web_url') }}</span>

                                    @endif

                                </div>

                            </div>
                            <div class="form-group">
                                <label for="business_description" class="col-md-12 col-form-label font-weight-bold">Business
                                    Description</label>
                                <textarea class="form-control" value="{{ auth()->user()->business_description }}" id="business_description" name="business_description"
                                          rows="3"></textarea>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn form-control"
                                        style="background-color: #E89342; color: #FFFFFF" id="submit">Complete & Get
                                    Notified
                                </button>
                            </div>
                        </form>
                        <script type="text/javascript">

                            $(document).ready(function (e) {


                                $('#image').change(function () {

                                    let reader = new FileReader();

                                    reader.onload = (e) => {

                                        $('#preview-image-before-upload').attr('src', e.target.result);
                                    }

                                    reader.readAsDataURL(this.files[0]);

                                });

                            });

                        </script>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
