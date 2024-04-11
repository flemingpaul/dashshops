@extends('dashboard')

@section('extra_css')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css
" rel="stylesheet">
@endsection

@section('content')
<main>
    <div class="container-fluid" style="background-color: #efefef">
        <div class="row ml-5">
            <div class="col-md-2 col-sm-2 mt-3" style="margin-left: 4%; margin-right: 12%;">
                <div class="card justify-content-center" style="width: 250px">
                    <div class="card-body">
                        @include('partials.menu',['active'=>'users'])
                        <script>
                            /* Code for changing active
                                link on clicking */
                            const btns =
                                $("#navigation .navbar-nav .nav-link");

                            for (let i = 0; i < btns.length; i++) {
                                btns[i].addEventListener("click",
                                    function() {
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
            <div class="col-md-2 m-1">
                <div class="card justify-content-center mt-2 mb-2">

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #00BCF9">
                        <p style="font-size: 12px">Total Members</p>
                    </div>

                    <div class="pt-2 pb-2">
                        <div class="bg-white rounded-lg pb-2 pt-2">
                            <!-- Progress bar 1 -->
                            <div class="progress mx-auto" data-value='100'>
                                <span class="progress-left">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold" style="color: #95CD58">{{ $total_members }}</div>
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
                        <p style="font-size: 12px">Total Retailers</p>
                    </div>

                    <div class="pt-2 pb-2">
                        <div class="bg-white rounded-lg pb-2 pt-2">
                            <!-- Progress bar 1 -->
                            <div class="progress mx-auto" data-value='100'>
                                <span class="progress-left">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold" style="color: #00BCF9">{{ $total_retailers }}</div>
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

                    <div class="card-header text-center text-white font-weight-bold" style="background-color: #E58821">
                        <p style="font-size: 12px">Total VIP Members</p>
                    </div>

                    <div class="pt-2 pb-2">
                        <div class="bg-white rounded-lg pb-2 pt-2">
                            <!-- Progress bar 1 -->
                            <div class="progress mx-auto" data-value='100'>
                                <span class="progress-left">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
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
    <!-- Toggle TAB -->
    <div class="container">
        <div class="col-md-12">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="active nav-item col-md-4 mt-4" role="presentation" style="margin-right: -0px">
                    <button class="nav-link active form-control" id="pills-home-tab" data-toggle="pill" data-target="#pills-app" type="button" role="tab" aria-controls="pills-app" aria-selected="true">
                        Members List
                    </button>
                </li>
                <li class="nav-item col-md-4 mt-4" role="presentation" style="margin-left: -0px!important;">
                    <button class="nav-link form-control" id="pills-profile-tab" data-toggle="pill" data-target="#pills-sales" type="button" role="tab" aria-controls="pills-sales" aria-selected="false">
                        Sales Members List
                    </button>
                </li>
                <li class="nav-item col-md-4 mt-4" role="presentation" style="margin-left: -0px!important;">
                    <button class="nav-link form-control" id="pills-vip-tab" data-toggle="pill" data-target="#pills-vips" type="button" role="tab" aria-controls="pills-vips" aria-selected="false">
                        VIP Members List
                    </button>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-app" role="tabpanel" aria-labelledby="pills-app-tab">
                <div class="container" style="margin-top: 5%; margin-bottom: 15%">

                    <div class="row justify-content-center">

                        <div class="col-md-12">

                            <div class="card p-4">
                                <table id="table_vip_users" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="margin-bottom: 10%">
                                            <th>Date Member Joined</th>
                                            <th>Email Address</th>
                                            <th>Zip Code</th>
                                            <th>Full Name</th>

                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($total_app_users as $user)
                                        <tr>
                                            <td>{{date('d-m-Y', strtotime($user->created_at))}}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->zip_code}}</td>
                                            <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                                            <td>
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="border: none">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
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
            <div class="tab-pane fade" id="pills-sales" role="tabpanel" aria-labelledby="pills-sales-tab">
                <div class="container" style="margin-top: 5%; margin-bottom: 15%">

                    <div class="row justify-content-center">

                        <div class="col-md-12">

                            <div class="card  p-4">
                                <table id="table_sales_users" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="margin-bottom: 10%">
                                            <th class="mb-5"></th>
                                            <th>Firstname</th>
                                            <th>Lastname</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Zip Code</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($total_sales_users??[] as $user)
                                        <tr>
                                            <td>
                                                <button type="button" class="btn" data-container="body" data-toggle="popover" data-placement="top" data-content="{{$user->phone_number}}">
                                                    <i class="fas fa-phone"></i>
                                                </button>
                                                <script>
                                                    $(function() {
                                                        $('[data-toggle="popover"]').popover()
                                                    })
                                                </script>
                                            </td>
                                            <td>{{ $user->firstname }}</td>
                                            <td>{{ $user->lastname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->business_address }}</td>
                                            <td>{{ $user->city }}</td>
                                            <td>{{ $user->state }}</td>
                                            <td>{{ $user->zip_code }}</td>
                                            <td>
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')" class="btn" style="border-radius: 40%">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
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
            <div class="tab-pane fade" id="pills-vips" role="tabpanel" aria-labelledby="pills-vips-tab">
                <div class="container" style="margin-top: 5%; margin-bottom: 15%">

                    <div class="row justify-content-center">

                        <div class="col-md-12">

                            <div class="card  p-4">
                                <table id="table_vips_users" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="margin-bottom: 10%">
                                            <th class="mb-5"></th>
                                            <th>Firstname</th>
                                            <th>Lastname</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Zip Code</th>
                                            <th>Status/Last Subscription</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($total_vip_users??[] as $user)
                                        <tr>
                                            <td>
                                                <button type="button" class="btn" data-container="body" data-toggle="popover" data-placement="top" data-content="{{$user->phone_number}}">
                                                    <i class="fas fa-phone"></i>
                                                </button>
                                                <script>
                                                    $(function() {
                                                        $('[data-toggle="popover"]').popover()
                                                    })
                                                </script>
                                            </td>
                                            <td>{{ $user->firstname }}</td>
                                            <td>{{ $user->lastname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->business_address }}</td>
                                            <td>{{ $user->city }}</td>
                                            <td>{{ $user->state }}</td>
                                            <td>{{ $user->zip_code }}</td>
                                            <td>
                                                <?php
                                                $date=date_format(date_create($user->last_subscription),"d-M-Y");
                                                if($user->expiry_date >= date('Y-m-d H:i:s')){
                                                    echo "Active/".$date;
                                                }else{
                                                    echo "Expired/".$date;
                                                }
                                                ?>
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
        </div>
    </div>
</main>

@endsection
@section('extra_js')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('js/members.js') }}"></script>

@endsection