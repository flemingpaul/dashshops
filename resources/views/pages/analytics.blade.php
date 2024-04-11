@extends('dashboard')

@section('extra_css')
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

                    <div class="row justify-content-center mt-3">
                        <div class="col-md-3 col-sm-3">
                            <form id="form_year" action="{{route('analytics')}}">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">By Year</label>
                                    <select class="form-control" id="exampleFormControlSelect1" name="year" onchange="submitform()">
                                        @for($i = 2023; $i<=(int)date('Y');$i++) <option value="{{$i}}" @if($i==(int)$year) selected @endif>{{$i}}</option>
                                            @endfor
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3 mb-5">
                        @if(Auth::user()->admin == 1)
                        <div class="col-md-3 col-sm-3">
                            <div class="card col-xl-12 col-lg-12 mb-4 ml-3 mt-4">
                                <div class="bg-white rounded-lg mt-5">
                                    <p class="font-weight-bold text-center">Total Number of Retailers</p>
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold">{{ $total_retailers }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg mt-4">
                                    <p class="font-weight-bold text-center">Total Number of Consumers</p>
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold">{{$total_consumers}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg mt-4">
                                    <p class="font-weight-bold text-center">Total Number of Banner Ads</p>
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold">{{ $total_banners }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg mt-4">
                                    <p class="font-weight-bold text-center">Total Number of VIP Members</p>
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold">{{ $total_vip }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg mt-4" style="margin-bottom: 70px">
                                    <p class="font-weight-bold text-center">Total Number of Coupon Offers</p>
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold">{{ $total_coupons }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <?php
                        $months=[
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
                        ?>
                        <div class="@if(Auth::user()->admin==1) col-md-3 col-sm-3 @else col-md-4 col-sm-4 @endif">
                            <div class="card col-xl-12 col-lg-12 col-sm-12 @if(Auth::user()->admin==2) ml-3 @endif mb-4 mt-4">
                                <p class="text-center font-weight-bold mt-3">Total Impression in the past 30 days</p>
                                <div class="bg-white rounded-lg mb-4">
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #7AB91D; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #7AB91D; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold" style="color: #7AB91D">{{$total_click_in_30_days}}</div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($months as $month)
                                <div class="d-flex flex-row justify-content-between mt-3">
                                    <h5>@if((int)$$month['clicks']>0)<a href="{{route('analytics-detail').'?year='.$year.'&month='.$month}}">{{ucfirst($month)}}</a>@else {{ucfirst($month)}} @endif</h5>
                                    <h5>{{ $$month['clicks'] }}</h5>
                                </div>
                                @endforeach
                               
                            </div>
                        </div>
                        <div class="@if(Auth::user()->admin==1) col-md-3 col-sm-3 @else col-md-4 col-sm-4 @endif">

                            <div class="card col-xl-12 col-lg-12 col-sm-12 mb-4 mt-4">
                                <p class="text-center font-weight-bold mt-3">Total Downloads in the past 30 days</p>
                                <div class="bg-white rounded-lg mb-4">
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #00B7F9; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #00B7F9; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold" style="color: #00B7F9">{{$total_download_in_30_days}}</div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($months as $month)
                                <div class="d-flex flex-row justify-content-between mt-3">
                                    <h5>@if((int)$$month['downloads']>0)<a href="{{route('analytics-detail').'?year='.$year.'&month='.$month}}">{{ucfirst($month)}}</a>@else {{ucfirst($month)}} @endif</h5>
                                    <h5>{{ $$month['downloads'] }}</h5>
                                </div>
                                @endforeach
                                
                            </div>
                        </div>
                        <div class="@if(Auth::user()->admin==1) col-md-3 col-sm-3 @else col-md-4 col-sm-4 @endif">
                            <div class="card col-xl-11 col-lg-11 col-sm-12 mb-4 mt-4">
                                <p class="text-center font-weight-bold mt-3">Total Redeemed in the past 30 days</p>
                                <div class="bg-white rounded-lg mb-4">
                                    <!-- Progress bar 1 -->
                                    <div class="progress mx-auto" data-value='100'>
                                        <span class="progress-left">
                                            <span class="progress-bar" style="border-color: #E58821; border-width: 10px"></span>
                                        </span>
                                        <span class="progress-right">
                                            <span class="progress-bar" style="border-color: #E58821; border-width: 10px"></span>
                                        </span>
                                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                            <div class="h2 font-weight-bold" style="color: #E58821">{{$total_redeemed_in_30_days}}</div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($months as $month)
                                <div class="d-flex flex-row justify-content-between mt-3">
                                    <h5>@if((int)$$month['redeemed']>0)<a href="{{route('analytics-detail').'?year='.$year.'&month='.$month}}">{{ucfirst($month)}}</a>@else {{ucfirst($month)}} @endif</h5>
                                    <h5>{{ $$month['redeemed'] }}</h5>
                                </div>
                                @endforeach
                                
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
    function submitform() {
        document.getElementById("form_year").submit();
    }
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
</script>
@endsection