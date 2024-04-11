@php use Carbon\Carbon; @endphp
@extends('dashboard')

@section('content')
<main>

    <div class="container-fluid" style="background-color: #efefef">
        <div class="row ml-5">
            <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                <div class="card justify-content-center" style="min-width: 150px; width: 250px">
                    <div class="card-body">
                        @include('partials.menu',['active'=>'coupons'])
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
                        <p style="font-size: 11px">Coupon Downloads</p>
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
                        <p style="font-size: 11px">Coupon Redeemed</p>
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

            <div class="col-md-12 col-sm-12">
                <div class="card mb-5">

                    <div class="card-body">

                        <form action="{{route('coupons')}}">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Search</label>
                                        <input type="text" name="search" value="{{$search}}" class="form-control" id="txtSearch" placeholder="Search Coupons">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Category</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="category">
                                            <option value="0" @if($category == 'All') selected @endif>All</option>
                                            @foreach($categories as $cat)  
                                                <option value="{{$cat->id}}" @if($category == $cat->id) selected @endif>{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $offerTypes =  [
                                    "Exclusive Offers",
                                    "Weekly Offers",
                                    "Daily Offers",
                                    "Local Offers",
                                ];
                                $approvalStatus =  [
                                    "New",
                                    "Approved",
                                    "Denied",
                                    "expired"
                                ];
                                ?>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Status</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="type">
                                            <option value="all" @if($type=="All") selected @endif>All</option>
                                            @foreach($approvalStatus as $offer)  
                                                <option value="{{$offer}}" @if($type == $offer) selected @endif>{{$offer}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Types</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="offer_type">
                                            <option value="all" @if($offertype == 'All') selected @endif>All</option>
                                            @foreach($offerTypes as $offer)  
                                                <option value="{{$offer}}" @if($offertype == $offer) selected @endif>{{$offer}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary mt-4">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <div class="card" style="width:100%">

                <div class="card-body">
                    <div class="col-md-12 mb-5">
                        @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                        @endif

                        <div class="row mt-5">
                            <?php
                            $offerTypes =  [
                                "Exclusive Offers",
                                "Weekly Offers",
                                "Daily Offers",
                                "Local Offers",
                            ];
                            ?>
                            @forelse($coupons as $coupon)
                            <?php
                            $status = "";
                            $status_main = "";
                            if (
                                strtolower($coupon->approval_status) == "new" || strtolower($coupon->approval_status) == "published"
                            ) {
                                $status = '<span class="text-warning"><i class="fas fa-hourglass-half"></i> </span>';
                                $status_main = '<span class="text-warning"><i class="fas fa-hourglass-half"></i> Pending</span>';
                            } elseif (
                                strtolower($coupon->approval_status) == "approved"
                            ) {
                                $status = '<span class="text-success"><i class="fas fa-check-circle"></i> </span>';
                                $status_main = '<span class="text-success"><i class="fas fa-check-circle"></i> Approved</span>';
                            } elseif (
                                strtolower($coupon->approval_status) == "denied"
                            ) {
                                $status = '<span class="text-danger"><i class="fas fa-ban"></i> </span>';
                                $status_main = '<span class="text-danger"><i class="fas fa-ban"></i> Denied</span>';
                            }
                            ?>
                            <div class="col-md-4">
                                <div class="card col-12">
                                    <img class="card-img-top" style="height: 12vw; object-fit: cover;" src="{{asset('images/'. $coupon->image) }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h5 class="card-title" style="text-align: center;">{!!$status!!} {{ $coupon->name }} <br><span style="font-size: 9px">{{$coupon->category_name}}</span></h5>
                                        <div class="row collapse" id="collapseCoupon_{{$coupon->id}}">

                                            <div class="col-12">
                                                <div class="row">

                                                    <div class="col-12 center" style="text-align:center">
                                                        <img class="avatar" src="{{asset('images/'. $coupon->banner_image) }}" style="width:70px;height:70px"><br>
                                                        <p><strong>{{$coupon->business_name}}</strong></p>
                                                        <p class="text-center font-weight-bold" style="font-size: 8px">{{$coupon->business_address}}
                                                            , {{ $coupon->city }}
                                                            , {{ $coupon->state }}
                                                            , {{ $coupon->zip_code }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">

                                                @if($coupon->discount_percentage == 'Discount Percent')
                                                <h2 class="text-center font-weight-bold" style="color: #858585">{{ $coupon->discount_now_price }}% Off</h2>
                                                @else
                                                <h2 class="text-center font-weight-bold" style="color: #858585; font-size:14pt">{{ $coupon->discount_percentage }}</h2>
                                                @endif
                                                <p class="card-text" style="text-align: justify;">{{ $coupon->discount_description }}</p>
                                                <p style="font-size: 9px">Offer #{{$coupon->id}} good
                                                    thru {{Carbon::parse($coupon->end_date)->toDateString()}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <a href="#collapseCoupon_{{$coupon->id}}" class="btn btn-primary" data-toggle="collapse" href="#collapseExample">...</a>
                                            </div>
                                            <div class="col-4">
                                                <a class="btn btn-success" data-toggle="modal" data-target="#confirmModal{{$coupon->id}}">View</a>
                                            </div>
                                            @if(Auth::user()->admin == 1)
                                            <div class="col-4">
                                                <a class="btn btn-danger" href="{{ route('admin.coupons.delete', $coupon->id) }}" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')">
                                                    Delete
                                                </a>
                                            </div>
                                           @endif
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="d-flex flex-row mt-5 justify-content-around">
                                        <a class="btn" data-toggle="modal" data-target="#confirmModal{{$coupon->id}}" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">
                                            View
                                        </a>
                                        <a class="btn" href="{{ route('admin.coupons.delete', $coupon->id) }}" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">
                                            Delete
                                        </a>
                                    </div>-->

                                <div class="modal fade" id="confirmModal{{$coupon->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Confirm
                                                    Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body d-flex flex-row mt-1 justify-content-around row">
                                                <div class="col-4">
                                                    <div class="card col-12" style="border:none">
                                                        <img class="card-img-top" style="height: 12vw; object-fit: cover;" src="{{asset('images/'. $coupon->image) }}" alt="Card image cap">
                                                        <div class="card-body">
                                                            {!! QrCode::size(160)->generate(base64_encode($coupon->qr_code)) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <h4>Retailer's Information</h4>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <img class="avatar" src="{{asset('images/'. $coupon->banner_image) }}" style="width:70px;height:70px"><br>
                                                        </div>
                                                        <div class="col-9">
                                                            <h6>Retailer's Name</h6>
                                                            <p>{{$coupon->business_name}}</p>

                                                            <h6 class="font-weight-bold">Retailer's Address</h6>
                                                            <p class="text-left ">{{$coupon->business_address}}
                                                                , {{ $coupon->city }}
                                                                , {{ $coupon->state }}
                                                                , {{ $coupon->zip_code }}</p>

                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <h4>Coupon Information <span style="font-size:10pt">{!!$status_main!!}</span></h4>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Name: </h6>
                                                            <p>{{ $coupon->name }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Price: </h6>
                                                            <p>
                                                                ${{ $coupon->price }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Category: </h6>
                                                            <p>{{ $coupon->category_name }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6>Coupon Discount</h6>
                                                            @if($coupon->discount_percentage == 'Discount Percent')
                                                            <p>{{ $coupon->discount_now_price }}% Off</p>
                                                            @else
                                                            <p>{{ $coupon->discount_percentage }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-12">
                                                            <h6 class="font-weight-bold">Coupon Description: </h6>
                                                            <p>{{ $coupon->discount_description }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Download
                                                                Limit: </h6>
                                                            <p>{{ $coupon->download_limit }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Retail Price: </h6>
                                                            <p>${{ $coupon->retail_price }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Start Date: </h6>
                                                            <p>{{ $coupon->start_date }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon End Date: </h6>
                                                            <p>{{ $coupon->end_date }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Discount Code: </h6>
                                                            <p>{{ $coupon->discount_code }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Coupon Offer Type: </h6>
                                                            <select class="form-control" id="select_{{$coupon->id}}">
                                                                @foreach($offerTypes as $offer)
                                                                <option value="{{$offer}}" @if($offer==$coupon->offer_type) selected @endif>{{$offer}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Created By: </h6>
                                                            <p>{{ $coupon->firstname }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="font-weight-bold">Created At: </h6>
                                                            <p>{{ $coupon->created_at }}</p>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                            @if(\Auth::user()->admin==1)
                                            <div class="modal-footer">

                                                <a class="btn btn-primary" href="javascript:approveCoupon('{{$coupon->id}}')">
                                                    Approve
                                                </a>
                                                <a class="btn btn-danger" href="{{ route('admin.coupons.deny', $coupon->id) }}">
                                                    Deny
                                                </a>

                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div>
                                <h3 class="text-center"> No Coupons Found </h3>
                            </div>
                            @endforelse
                            {{-- <div class="col-md-4">--}}
                            {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                            {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                            {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                            {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                            {{-- Approve--}}
                            {{-- </a>--}}
                            {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                            {{-- Deny--}}
                            {{-- </a>--}}
                            {{-- </div>--}}
                            {{-- </div>--}}
                            {{-- <div class="col-md-4">--}}
                            {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                            {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                            {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                            {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                            {{-- Approve--}}
                            {{-- </a>--}}
                            {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                            {{-- Deny--}}
                            {{-- </a>--}}
                            {{-- </div>--}}
                            {{-- </div>--}}
                            {{-- <div class="col-md-4">--}}
                            {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                            {{-- <img src="{{asset('images/coupon3.png')}}" alt="coupon 1" width="100%">--}}
                            {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                            {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                            {{-- Approve--}}
                            {{-- </a>--}}
                            {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                            {{-- Deny--}}
                            {{-- </a>--}}
                            {{-- </div>--}}
                            {{-- </div>--}}

                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                {{$coupons->links('pagination.home')}}
                            </div>
                        </div>
                        {{-- <div class="row mt-5">--}}
                        {{-- --}}{{-- @forelse ($coupon as $coupons)--}}
                        {{-- --}}{{-- <img src="{{ $coupon->image }}" alt=""/>--}}
                        {{-- --}}{{-- <h4>{{$coupon->name}}</h4>--}}
                        {{-- --}}{{-- @empty--}}
                        {{-- --}}{{-- <div>--}}
                        {{-- --}}{{-- No coupons found--}}
                        {{-- --}}{{-- </div>--}}
                        {{-- --}}{{-- @endforelse--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon3.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="row mt-5">--}}
                        {{-- --}}{{-- @forelse ($coupon as $coupons)--}}
                        {{-- --}}{{-- <img src="{{ $coupon->image }}" alt=""/>--}}
                        {{-- --}}{{-- <h4>{{$coupon->name}}</h4>--}}
                        {{-- --}}{{-- @empty--}}
                        {{-- --}}{{-- <div>--}}
                        {{-- --}}{{-- No coupons found--}}
                        {{-- --}}{{-- </div>--}}
                        {{-- --}}{{-- @endforelse--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon3.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="row mt-5">--}}
                        {{-- --}}{{-- @forelse ($coupon as $coupons)--}}
                        {{-- --}}{{-- <img src="{{ $coupon->image }}" alt=""/>--}}
                        {{-- --}}{{-- <h4>{{$coupon->name}}</h4>--}}
                        {{-- --}}{{-- @empty--}}
                        {{-- --}}{{-- <div>--}}
                        {{-- --}}{{-- No coupons found--}}
                        {{-- --}}{{-- </div>--}}
                        {{-- --}}{{-- @endforelse--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon3.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="row mt-5">--}}
                        {{-- --}}{{-- @forelse ($coupon as $coupons)--}}
                        {{-- --}}{{-- <img src="{{ $coupon->image }}" alt=""/>--}}
                        {{-- --}}{{-- <h4>{{$coupon->name}}</h4>--}}
                        {{-- --}}{{-- @empty--}}
                        {{-- --}}{{-- <div>--}}
                        {{-- --}}{{-- No coupons found--}}
                        {{-- --}}{{-- </div>--}}
                        {{-- --}}{{-- @endforelse--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon2.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- <div class="col-md-4">--}}
                        {{-- <h5 class="font-weight-bold mt-2 mb-2">Category Name</h5>--}}
                        {{-- <img src="{{asset('images/coupon3.png')}}" alt="coupon 1" width="100%">--}}
                        {{-- <div class="d-flex flex-row mt-4 justify-content-around">--}}
                        {{-- <a class="btn" href="#" style=" width: 90px; background-color: #3E9180; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Approve--}}
                        {{-- </a>--}}
                        {{-- <a class="btn" href="#" style="width: 90px; background-color: #ED362B; color: #FFFFFF; border-radius: 10px">--}}
                        {{-- Deny--}}
                        {{-- </a>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                        {{-- </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

@endsection

@section('extra_js')
<script>
    function approveCoupon(couponId) {
        var offer = document.getElementById(`select_${couponId}`).value;
        var url = get_base_url(`coupons/${couponId}/${offer}/approve`);
        window.location.replace(url);
    }
</script>
@endsection