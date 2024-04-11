@extends('dashboard')

@section('extra_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .progress {
        width: 120px;
        height: 120px;
        background: none;
        position: relative;
    }

    .progress::after {
        content: "";
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 6px solid #eee;
        position: absolute;
        top: 0;
        left: 0;
    }

    .progress>span {
        width: 50%;
        height: 100%;
        overflow: hidden;
        position: absolute;
        top: 0;
        z-index: 1;
    }

    .progress .progress-left {
        left: 0;
    }

    .progress .progress-bar {
        width: 100%;
        height: 100%;
        background: none;
        border-width: 6px;
        border-style: solid;
        position: absolute;
        top: 0;
    }

    .progress .progress-left .progress-bar {
        left: 100%;
        border-top-right-radius: 80px;
        border-bottom-right-radius: 80px;
        border-left: 0;
        -webkit-transform-origin: center left;
        transform-origin: center left;
    }

    .progress .progress-right {
        right: 0;
    }

    .progress .progress-right .progress-bar {
        left: -100%;
        border-top-left-radius: 80px;
        border-bottom-left-radius: 80px;
        border-right: 0;
        -webkit-transform-origin: center right;
        transform-origin: center right;
    }

    .progress .progress-value {
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
@endsection

@section('content')
<main>

    <div class="container-fluid" style="background-color: #efefef">
        <div class="row ml-5">
            <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                <div class="card justify-content-center" style="min-width: 150px; width: 250px">
                    <div class="card-body">
                        @include('partials.menu',['active'=>'analytics'])
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
    <div class="container" style="margin-top: 5%; margin-bottom: 15%">

        <div class="row justify-content-center">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">
                            Analytics Details -
                            <?php
                            $months = [
                               'january',
                               'february',
                               'march',
                               'april',
                               'may',
                               'june',
                               'july',
                               'august',
                               'september',
                               'october',
                               'november',
                               'december'                              
                            ];

                            
                            if ($retailer_id == 0) {
                                echo "All Retailers";
                            } else {
                                echo $retailer->business_name;
                            }
                            if ($coupon_id != 0) {
                                echo " - " . $coupon->name . " ";
                            } else {
                                echo " - All Coupons ";
                            }
                            echo "[".ucfirst($month).", $year]";
                            ?>

                        </h5>
                        <div class="">
                            <form id="form_year" class="row justify-content-center mt-3" action="{{route('analytics-detail')}}">
                                <div class="col-md-3 col-sm-3">
                                    <input type="hidden" name="coupon_id" id="coupon_id" value="{{$coupon_id}}">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Retailers</label>
                                        <select class="form-control js-example-basic-single" id="sel_retailer" name="retailer_id" onchange="resetCouponId()">
                                            <option value="0">All Retailers</option>
                                            @foreach($retailers as $retailer)
                                            <option value="{{$retailer->id}}" @if($retailer->id == $retailer_id) selected @endif>{{$retailer->business_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">By Year</label>
                                        <select class="form-control js-example-basic-single" id="exampleFormControlSelect1" name="year">
                                            @for($i = 2023; $i<=(int)date('Y');$i++) <option value="{{$i}}" @if($i==(int)$year) selected @endif>{{$i}}</option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1x">By Month</label>
                                        <select class="form-control js-example-basic-single" id="exampleFormControlSelect1x" name="month">
                                            @foreach($months as $mon)) 
                                            <option value="{{$mon}}" @if($mon==$month) selected @endif>{{ucfirst($mon)}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-info">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
                <div class="card mt-5">

                    <div class="card-body">
                        <h6 class="card-title">Impressions</h6>
                        <table id="table_impressions" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr style="margin-bottom: 10%">
                                    <th class="mb-5">S/N</th>
                                    <th class="mb-5">Date</th>
                                    <th>Coupon</th>
                                    <th>Retailer</th>
                                    <!--<th>Impression</th>-->
                                    <th>Click - City</th>
                                    <th>Click - State</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; // count($coupon_clicks)+1;
                                ?>
                                @foreach ($coupon_clicks??[] as $coupon)
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
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        <?php
                                        $date = date_create($coupon->date_created);

                                        ?>
                                        {{ date_format($date, 'j-M-Y h:i a') }}
                                    </td>
                                    <td><a href="{{route('analytics-detail').'?year='.$year.'&month='.$month.'&coupon_id='.$coupon->id}}" title="View Analytics for the Coupon">{{ $coupon->name }}</a></td>
                                    <td>{{ $coupon->business_name }}</td>
                                    <td>{{ $coupon->city ?? "-" }}</td>
                                    <td>{{ $coupon->state ?? "-" }}</td>

                                    <td>

                                        <a data-toggle="modal" data-target="#confirmModal{{$coupon->id}}" title="View Coupon">
                                            <i class="fas fa-eye" style="font-size: 22px;
                                               color: black;"></i>
                                        </a>
                                        <div class="modal fade" id="confirmModal{{$coupon->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Coupon Details</h5>
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
                                                                    <p>{{$coupon->offer_type}} </p>
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

                                                </div>
                                            </div>
                                        </div>

                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-5">

                    <div class="card-body">
                        <h6 class="card-title">Downloads</h6>
                        <table id="table_downloads" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr style="margin-bottom: 10%">
                                    <th class="mb-5">S/N</th>
                                    <th class="mb-5">Date</th>
                                    <th>Coupon</th>
                                    <th>Retailer</th>
                                    <!--<th>Impression</th>-->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; // count($coupon_clicks)+1;
                                ?>
                                @foreach ($coupon_downloads??[] as $coupon)
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
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        <?php
                                        $date = date_create($coupon->date_created);

                                        ?>
                                        {{ date_format($date, 'j-M-Y h:i a') }}
                                    </td>
                                    <td><a href="{{route('analytics-detail').'?year='.$year.'&month='.$month.'&coupon_id='.$coupon->id}}" title="View Analytics for the Coupon">{{ $coupon->name }}</a></td>
                                    <td>{{ $coupon->business_name }}</td>

                                    <td>

                                        <a data-toggle="modal" data-target="#confirmModal{{$coupon->id}}" title="View Coupon">
                                            <i class="fas fa-eye" style="font-size: 22px;
                                               color: black;"></i>
                                        </a>
                                        <div class="modal fade" id="confirmModal{{$coupon->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Coupon Details</h5>
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
                                                                    <p>{{$coupon->offer_type}} </p>
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

                                                </div>
                                            </div>
                                        </div>

                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-5">

                    <div class="card-body">
                        <h6 class="card-title">Redemptions</h6>
                        <table id="table_redemptions" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr style="margin-bottom: 10%">
                                    <th class="mb-5">S/N</th>
                                    <th class="mb-5">Date</th>
                                    <th>Coupon</th>
                                    <th>Retailer</th>
                                    <!--<th>Impression</th>-->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; // count($coupon_clicks)+1;
                                ?>
                                @foreach ($coupon_redeemeds??[] as $coupon)
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
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        <?php
                                        $date = date_create($coupon->date_created);

                                        ?>
                                        {{ date_format($date, 'j-M-Y h:i a') }}
                                    </td>
                                    <td><a href="{{route('analytics-detail').'?year='.$year.'&month='.$month.'&coupon_id='.$coupon->id}}" title="View Analytics for the Coupon">{{ $coupon->name }}</a></td>
                                    <td>{{ $coupon->business_name }}</td>

                                    <td>

                                        <a data-toggle="modal" data-target="#confirmModal{{$coupon->id}}" title="View Coupon">
                                            <i class="fas fa-eye" style="font-size: 22px;
                                               color: black;"></i>
                                        </a>
                                        <div class="modal fade" id="confirmModal{{$coupon->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Coupon Details</h5>
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
                                                                    <p>{{$coupon->offer_type}} </p>
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

                                                </div>
                                            </div>
                                        </div>

                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                        </table>
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
<script>
    $(document).ready(function() {
        new DataTable('#table_impressions');
        new DataTable('#table_downloads');
        new DataTable('#table_redemptions');
        //new DataTable('#table_created_retailers');
        $('.js-example-basic-single').select2();

    })
</script>
<script>
    $(function() {

        $(".progress").each(function() {

            var value = $(this).attr('data-value');
            var left = $(this).find('.progress-left .progress-bar');
            var right = $(this).find('.progress-right .progress-bar');

            if (value > 0) {
                if (value <= 50) {
                    right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
                } else {
                    right.css('transform', 'rotate(180deg)')
                    left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
                }
            }

        })

        function percentageToDegrees(percentage) {

            return percentage / 100 * 360

        }

    });
    function resetCouponId(){
        $('#coupon_id').val(0);
    }

</script>
@endsection