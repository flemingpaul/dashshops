@extends('dashboard')

@section('content')
<style>
    input[type=file]::file-selector-button {
        margin-right: 20px;
        border: none;
        background: #084cdf;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
        cursor: pointer;
        transition: background .2s ease-in-out;
    }

    input[type=file]::file-selector-button:hover {
        background: #0d45a5;
    }

    .drop-container {
        position: relative;
        display: flex;
        gap: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 200px;
        padding: 20px;
        border-radius: 10px;
        border: 2px dashed #555;
        color: #444;
        cursor: pointer;
        transition: background .2s ease-in-out, border .2s ease-in-out;
    }

    .drop-container:hover {
        background: #eee;
        border-color: #111;
    }

    .drop-container:hover .drop-title {
        color: #222;
    }

    .drop-title {
        color: #444;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        transition: color .2s ease-in-out;
    }
</style>
<main>

    <div class="container-fluid" style="background-color: #efefef">
        <div class="row ml-5">
            <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                <div class="card justify-content-center" style="min-width: 150px; width: 250px">
                    <div class="card-body">
                        @include('partials.menu',['active'=>'add-coupon'])
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

        <div class="row justify-content-center">

            <div class="col-md-11">
                <div class="card">

                    <div  >
                        {{-- <form method="post" action="{{ route('files.store') }}"--}}
                        {{-- style="border-color: #FFFFFF!important; height: 70px"--}}
                        {{-- enctype="multipart/form-data"--}}
                        {{-- class="dropzone" id="dropzone">--}}
                        {{-- <div class="dz-message" data-dz-message>--}}
                        {{-- <img src="{{asset('images/upload.png')}}" alt="upload"/>--}}
                        {{-- <h5 class="mt-2 font-weight-bold">Drag and Drop to Upload</h5>--}}
                        {{-- </div>--}}
                        {{-- @csrf--}}
                        {{-- </form>--}}
                        @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                        @endif
                        <form action="{{ route('coupon.create') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                    </div>
                    {{-- <div class="row mt-3">--}}
                    {{-- <div class="col-lg-12 margin-tb">--}}
                    {{-- <div class="text-center">--}}
                    {{-- <a class="btn btn-primary p-2"--}}
                    {{-- title="Upload files"> Upload Image--}}
                    {{-- </a>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}
                    {{-- </div>--}}


                    <div class="card-body">
                        <div class="row justify-content-center ">
                            <div class="col-md-5" style="border: 1px solid #EFEFEF">
                                <div class="card drop-container col-md-12 mb-5" id="dropcontainer" style="border-color: #FFFFFF; background-size: cover; height:35% !important;  box-shadow: 5px 4px #efefef;">
                                    <div class="col-md-12 mb-2" style="height: 250px;width: 100%; background-size: cover;" id="preview-image-before-upload">
                                        <!--<img  src="" alt="Offer Image" style="width: 100%;object-fit: cover">-->
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <span class="drop-title">Drop files here</span><br>
                                            or <br>
                                            <input type="file" name="image" placeholder="Choose image" id="image" accept="images/*">
                                            @error('image')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    Use high quality JPGG/PNG file that is not more than 600 by 400 pixels and 2MB in size
                                </div>
                            </div>

                            <div class="col-md-7" style="border: 1px solid #EFEFEF">

                                <div class="form-group row mt-5">
                                    <input type="hidden" name="has_new_image" value="0" id="has_new_image">

                                    <div class="col-md-6 col-sm-12">
                                        <label for="name" class="col-md-12 col-sm-12 font-weight-bold col-form-label">Coupon / Offer
                                            Name</label>

                                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" required autofocus>

                                        @if ($errors->has('name'))

                                        <span class="text-danger">{{ $errors->first('name') }}</span>

                                        @endif

                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label for="price" class="col-md-12 col-sm-12 font-weight-bold col-form-label">Original Offer Price:</label>

                                        <input type="number" id="price" class="form-control" name="price" value="{{old('price')}}" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" required autofocus>

                                        @if ($errors->has('price'))

                                        <span class="text-danger">{{ $errors->first('price') }}</span>

                                        @endif

                                    </div>

                                </div>


                                <div class="form-group row mt-5">

                                    <div class="col-md-6 col-sm-12">
                                        <label for="category" class="col-md-12 font-weight-bold col-form-label">Coupon
                                            Category</label>
                                        <select id="category" class="form-control" name="category_id" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;">
                                            <option value="0">Select Category</option>
                                            @forelse ( $categories as $category)
                                            <option value="{{ $category->id }}" @if((int)($category->id) == (int)old('category_id')) selected @endif >{{ $category->name }}</option>
                                            @empty
                                            <option value=""></option>
                                            @endforelse
                                        </select>
                                        @if ($errors->has('category'))

                                        <span class="text-danger">{{ $errors->first('category') }}</span>

                                        @endif

                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label for="retailer" class="col-md-12 font-weight-bold col-form-label">Select
                                            Retailer</label>

                                        <select id="retailer" class="form-control" name="retailer_id" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" required>
                                            <option value=""></option>
                                            @forelse ( $retailers as $retailer)
                                            <option value="{{ $retailer->id }}">{{ $retailer->business_name }}</option>
                                            @empty
                                            <option value=""></option>
                                            @endforelse
                                        </select>
                                        @if ($errors->has('retailer_id'))

                                        <span class="text-danger">{{ $errors->first('retailer_id') }}</span>

                                        @endif

                                    </div>



                                </div>


                                <div class="form-group row mt-5">


                                    <div class="col-md-6 col-sm-6">
                                        <div class="d-flex flex-row mb-3">
                                            <input type="radio" name="selectedDiscountType" id="discount_percentage_select" value="Discount Percent" @if(old('selectedDiscountType')=="Discount Percent" ) checked @endif required onclick="setDiscountType()">
                                            <label for="discount_percentage_select" class="col-md-12 col-sm-12 font-weight-bold col-form-label">Coupon
                                                discount Percentage(%)</label>

                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="d-flex flex-row mb-3">
                                            <input type="radio" id="discount_now_select" name="selectedDiscountType" value="discount_now_price" class="form-check-input" onclick="setDiscountType()" @if(old('selectedDiscountType') !="Discount Percent" ) checked @endif requireds>
                                            <label for="discount_now_select" class="col-md-12 col-sm-12 font-weight-bold col-form-label form-check-label">Coupon
                                                Now Discount Price</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <input type="text" id="discountAmount" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" class="form-control" name="discountAmount" required>

                                        @if ($errors->has('selectedDiscountType'))
                                        <span class="text-danger">{{ $errors->first('selectedDiscountType') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group row mt-5">
                                    <label for="discount_now" class="col-md-12 col-sm-12 font-weight-bold col-form-label">Coupon
                                        Discount Description</label>

                                    <div class="col-md-12 col-sm-12">

                                        <input type="text" id="discount_description" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" class="form-control" name="discount_description">

                                        @if ($errors->has('discount_description'))

                                        <span class="text-danger">{{ $errors->first('discount_description') }}</span>

                                        @endif

                                    </div>
                                </div>
                                <div class="form-group row mt-5">

                                    <div class="col-md-6 col-sm-12">
                                        <label for="start_date" class="col-md-12 col-sm-12 font-weight-bold col-form-label">Start
                                            Date</label>

                                        <input type="date" id="start_date" class="form-control" value="{{old('start_date')}}" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" name="start_date" required>

                                        @if ($errors->has('start_date'))

                                        <span class="text-danger">{{ $errors->first('start_date') }}</span>

                                        @endif

                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date" class="col-md-12 font-weight-bold col-form-label">End
                                            Date</label>

                                        <input type="date" id="end_date" class="form-control" value="{{old('end_date')}}" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" name="end_date" required>

                                        @if ($errors->has('end_date'))

                                        <span class="text-danger">{{ $errors->first('end_date') }}</span>

                                        @endif

                                    </div>

                                </div>

                                <div class="form-group row mt-4">

                                    <div class="col-md-6">
                                        <label for="offer_type" class="col-md-12 col-sm-12 font-weight-bold col-form-label">Coupon
                                            Offer Type</label>
                                        <select id="offer_type" class="form-control" name="offer_type" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" required>
                                            <option value="">Coupon Offer Type</option>
                                            <option value="Exclusive Offers" @if(old('offer_type')=="Exclusive Offers" ) selected @endif>Exclusive Offers</option>
                                            <option value="Weekly Offers" @if(old('offer_type')=="Weekly Offers" ) selected @endif>Weekly Offers</option>
                                            <option value="Daily Offers" @if(old('offer_type')=="Daily Offers" ) selected @endif>Daily Offers</option>
                                            <option value="Local Offers" @if(old('offer_type')=="Local Offers" ) selected @endif>Local Offers</option>
                                        </select>
                                        @if ($errors->has('offer_type'))

                                        <span class="text-danger">{{ $errors->first('offer_type') }}</span>

                                        @endif

                                    </div>


                                    <div class="col-md-6 col-sm-6">
                                        <label for="download_limit" class="col-md-12 col-sm-12 font-weight-bold col-form-label">Download Limit
                                        </label>
                                        <input type="number" id="download_limit" style="border-color: #FFFFFF;  box-shadow: 5px 4px #efefef;" class="form-control" name="download_limit" value="{{old('download_limit')}}">

                                        @if ($errors->has('download_limit'))

                                        <span class="text-danger">{{ $errors->first('download_limit') }}</span>

                                        @endif

                                    </div>
                                </div>

                            </div>



                            <div class="col-md-6 offset-md-3">

                                <button type="submit" class="btn btn-primary form-control text-white" style="margin-top: 10%; margin-bottom: 10%; height: 50px">

                                    Add coupon

                                </button>

                            </div>

                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</main>
@endsection

@section('extra_js')
<!--<script src="https://code.jquery.com/jquery-3.7.0.js"></script>-->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('js/addcoupon.js')}}"></script>
@endsection