@php use Carbon\Carbon; @endphp
@extends('dashboard')

@section('content')
<main>

    <div class="container-fluid" style="background-color: #efefef">
        <div class="row ml-5">
            <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                <div class="card justify-content-center" style="min-width: 150px; width: 250px">
                    <div class="card-body">
                        @include('partials.menu',['active'=>'products'])
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
                                        <input type="text" name="search" value="{{$search}}" class="form-control" id="txtSearch" placeholder="Search Products">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Category</label>
                                        <select class="form-control" id="selectCategory" name="category">
                                            <option value="0" @if($category=='All' ) selected @endif>All</option>
                                            @foreach($categories as $cat)
                                            <option value="{{$cat->id}}" @if($category==$cat->id) selected @endif>{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <?php

                                $approvalStatus =  [
                                    "New",
                                    "Approved",
                                    "Denied",
                                ];
                                ?>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Status</label>
                                        <select class="form-control" id="exampleFormControlSelect1" name="type">
                                            <option value="all" @if($type=="All" ) selected @endif>All</option>
                                            @foreach($approvalStatus as $offer)
                                            <option value="{{$offer}}" @if($type==$offer) selected @endif>{{$offer}}</option>
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
<script src="{{asset('assets/admin/assets/js/app/products-view.js?'.time())}}"></script>
@endsection