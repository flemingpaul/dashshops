@php use Carbon\Carbon; @endphp
@extends('dashboard')


@section('extra_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
</style>
@endsection

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

                        <div class="row">
                            
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Retailers</label>
                                    <select class="form-control" id="selectRetailer" name="category">
                                        <option value="0">All</option>
                                        @foreach($retailers as $retailer)
                                        <option value="{{$retailer->id}}">{{$retailer->business_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Category</label>
                                    <select class="form-control" id="selectCategory" name="category">
                                        <option value="0">All</option>
                                        @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <?php

                            $approvalStatus =  [
                                ['name' => "New", 'value' => -1],
                                [
                                    'name' => "Approved", 'value' => 1
                                ],
                                [
                                    'name' => "Denied", 'value' => 0
                                ],
                            ];
                            ?>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="cmbStatus">Status</label>
                                    <select class="form-control" id="cmbStatus" name="type">
                                        <option value="all" @if($type=="All" ) selected @endif>All</option>
                                        @foreach($approvalStatus as $offer)
                                        <option value="{{$offer['value']}}" @if($type==$offer['value']) selected @endif>{{$offer['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-2">
                                <button type="button" onclick="hSearch()" class=" btn btn-primary mt-4">Search</button>
                            </div>
                        </div>
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

                        <div id="divTableProducts">

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
<script>
    function approveCoupon(couponId) {
        var offer = document.getElementById(`select_${couponId}`).value;
        var url = get_base_url(`coupons/${couponId}/${offer}/approve`);
        window.location.replace(url);
    }
</script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('js/choices.min.js?'.time())}}"></script>
<script src="{{asset('js/products-view.js?'.time())}}"></script>
@endsection