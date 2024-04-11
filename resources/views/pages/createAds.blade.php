@extends('dashboard')

@section('content')
    <main>
        <div class="container-fluid" style="background-color: #efefef">
            <div class="row ml-5">
                <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                    <div class="card justify-content-center" style="min-width: 150px; width: 250px">
                        <div class="card-body">
                        @include('partials.menu',['active'=>'analytics'])
                            <script type="text/javascript">
                                $(document).on('click', '.nav-pills li', function () {
                                    $(".nav-pills li").removeClass("active");
                                    $(this).addClass("active");
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 m-1">
                    <div class="card justify-content-center mt-2 mb-2">

                        <div class="card-header text-center text-white font-weight-bold"
                             style="background-color: #7AB91D">
                            <p style="font-size: 12px">Coupon Clicks</p>
                        </div>

                        <div class="card-body">
                            <img src="{{asset('images/clicks.png')}}" alt="downloads" width="90%"/>
                        </div>
                        <div class="card-footer">
                            <h4 class="text-center">130</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3 m-1">
                    <div class="card justify-content-center mt-2 mb-2">

                        <div class="card-header text-center text-white font-weight-bold"
                             style="background-color: #00B7F9">
                            <p style="font-size: 12px">Coupon Downloads</p>
                        </div>

                        <div class="card-body">
                            <img src="{{asset('images/downloads.png')}}" alt="downloads" width="90%"/>
                        </div>
                        <div class="card-footer">
                            <h4 class="text-center">130</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 m-1">
                    <div class="card justify-content-center mt-2 mb-2">

                        <div class="card-header text-center text-white font-weight-bold"
                             style="background-color: #E58821">
                            <p style="font-size: 12px">Coupon Redeemed</p>
                        </div>

                        <div class="card-body">
                            <img src="{{asset('images/redeemed.png')}}" alt="downloads" width="90%"/>
                        </div>
                        <div class="card-footer">
                            <h4 class="text-center">130</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>

        </div>
    </main>
@endsection
