@extends('dashboard')

@section('content')
    <main>
        <div class="container-fluid" style="background-color: #efefef">
            <div class="row ml-5">
                <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                    <div class="card justify-content-center" style="width: 250px">
                        <div class="card-body">
                        @include('partials.menu',['active'=>'retailers'])
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
                <div class="col-md-2 col-sm-3 m-1">
                    <div class="card justify-content-center mt-2 mb-2">

                        <div class="card-header text-center text-white font-weight-bold" style="background-color: #00BCF9">
                            <p style="font-size: 12px">New Retailers</p>
                        </div>

                        <div class="p-1">
                            <div class="bg-white rounded-lg pt-2 pb-2">
                                <!-- Progress bar 1 -->
                                <div class="progress mx-auto" data-value='100'>
                                <span class="progress-left">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                    <span class="progress-right">
                                    <span class="progress-bar"
                                          style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                    <div
                                        class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center"><div class="h2 font-weight-bold" style="color: #95CD58">
                                            {{ $total_retailers }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <h4 class="text-center p-2"></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 m-1">
                    <div class="card justify-content-center mt-2 mb-2">

                        <div class="card-header text-center text-white font-weight-bold" style="background-color: #95CD58">
                            <p style="font-size: 12px">Approved Retailers</p>
                        </div>

                        <div class="p-1">
                            <div class="bg-white rounded-lg pt-2 pb-2">
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
                                        <div class="h2 font-weight-bold" style="color: #00BCF9">{{ $total_approved }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <h4 class="text-center p-2"></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3 m-1">
                    <div class="card justify-content-center mt-2 mb-2">

                        <div class="card-header text-center text-white font-weight-bold" style="background-color: #E58821">
                            <p style="font-size: 12px">VIP Members</p>
                        </div>

                        <div class="p-1">
                            <div class="bg-white rounded-lg pt-2 pb-2">
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
                            <h4 class="text-center p-2"></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center" style="margin-top: 5%">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-sm-12 mt-4" style="margin-bottom: 15%">
                                        <div style="margin-top: 5%">
                                            <h3 class="font-weight-bold">Business Name:</h3>
                                            <h5 class="text-black-50">{{$retailer->business_name}}</h5>
                                        </div>
                                        <div style="margin-top: 10%">
                                            <h3 class="font-weight-bold">Business Category:</h3>
                                            <h5 class="text-black-50">{{$retailer->category->name}}</h5>
                                        </div>
                                        <div style="margin-top: 10%">
                                            <h3 class="font-weight-bold">Business Description:</h3>
                                            <h5 class="text-black-50">{{$retailer->business_description}}</h5>
                                        </div>
                                        <div style="margin-top: 10%">
                                            <h3 class="font-weight-bold">Business Address:</h3>
                                            <h5 class="text-black-50">{{$retailer->business_address}}</h5>
                                        </div>
                                        <div style="margin-top: 10%">
                                            <h3 class="font-weight-bold">City:</h3>
                                            <h5 class="text-black-50">{{$retailer->city}}</h5>
                                        </div>
                                        <div style="margin-top: 10%; display: flex; flex-direction: row; justify-content: space-between">
                                            <div>
                                                <h3 class="font-weight-bold">Email:</h3>
                                                <h5 class="text-black-50">{{$retailer->email}}</h5>
                                            </div>
                                            <div>
                                                <h3 class="font-weight-bold">Phone:</h3>
                                                <h5 class="text-black-50">{{$retailer->phone_number}}</h5>
                                            </div>
                                        </div>
                                    <div style="margin-top: 10%; display: flex; flex-direction: row; justify-content: space-between">
                                            <div>
                                                <h3 class="font-weight-bold">State</h3>
                                                <h5 class="text-black-50">{{$retailer->state}}</h5>
                                            </div>
                                            <div>
                                                <h3 class="font-weight-bold">Zip Code:</h3>
                                                <h5 class="text-black-50">{{$retailer->zip_code}}</h5>
                                            </div>
                                        </div>
                                        <div style="margin-top: 10%">
                                            <h3 class="font-weight-bold">Web Url:</h3>
                                            <h5 class="text-black-50">
                                                <a href="{{$retailer->web_url}}" target="_blank">{{$retailer->web_url}}</a></h5>
                                        </div>
                                        <div style="margin-top: 10%">
                                            <h3 class="font-weight-bold">Created By:</h3>
                                            <h5 class="text-black-50">{{$retailer->user->lastname}}  {{  $retailer->user->firstname }}</h5>
                                        </div>
                                </div>
                                <div class="col-md-4 col-sm-12 justify-content-center mt-5">
                                    <img src="{{asset('images/'.$retailer->banner_image)}}" alt="logo" style="width: 300px; height: 300px" />
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$retailer->id}}">
                                        Update Logo
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{$retailer->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Update Logo</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" enctype="multipart/form-data" id="upload-image" action="{{ route('update-logo', $retailer->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <input type="file" name="banner_image" placeholder="Choose image" id="image">
                                                                    @error('banner_image')
                                                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 mb-2">
                                                                <img id="preview-image-before-upload" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif"
                                                                     alt="preview image" style="max-height: 250px; width: 100%">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

                                                <script type="text/javascript">

                                                    $(document).ready(function (e) {


                                                        $('#image').change(function(){

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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
