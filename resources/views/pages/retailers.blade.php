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
                        @include('partials.menu',['active'=>'retailers'])
                        
                            <script type="text/javascript">
                                $(document).on('click', '.nav-pills li', function() {
                                    $(".nav-pills li").removeClass("active");
                                    $(this).addClass("active");
                                });
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
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                    <div class="h2 font-weight-bold" style="color: #95CD58">
                                        {{ $total_retailers }}
                                    </div>
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
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar" style="border-color: #000000; border-width: 10px"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
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
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 5%">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">

                        @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                        @endif

                        @if(auth()->user()->admin)
                        <table id="table_retailers" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr style="margin-bottom: 10%">
                                    <th class="mb-5">Date Retailer Joined</th>
                                    <th>Retailer Name</th>
                                    <th>Retailer State</th>
                                    <th>Retailer Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($created_retailers ?? [] as $retailer)
                                <tr>
                                    <td>{{ $retailer->created_at }}</td>
                                    <td>{{ $retailer->business_name }}</td>
                                    <td>{{ $retailer->state }}</td>
                                    <td>{{ $retailer->approval_status }}</td>

                                    <td>
                                        @if(auth()->user()->admin ==1)
                                        <a href="{{ route('admin.retailers.approve', $retailer->id) }}" title="Approve Retailer">
                                            <i class="fas fa-check-circle" style="font-size: 22px;
                                               color: green;"></i>
                                        </a>
                                        <a href="{{ route('admin.retailers.deny', $retailer->id) }}" class="" title="Deny Retailer">
                                            <i class="fas fa-minus-circle" style="font-size: 22px;
                                               color: red;"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('retailer', $retailer->id, $retailer) }}" title="View Retailer">
                                            <i class="fas fa-eye" style="font-size: 22px;
                                               color: black;"></i>
                                        </a>
                                        @if(auth()->user()->admin ==1)
                                        <form action="{{ route('admin.retailers.delete', $retailer->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')" title="Delete Retailer" style="border-radius: 40%; margin-top: -5px; background-color: inherit">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                    
                                </tr>
                            

                            @endforeach
                            </tbody>
                        </table>
                        @else
                        <table id="table_retailers" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr style="margin-bottom: 10%">
                                    <th class="mb-5">Date Retailer Joined</th>
                                    <th>Retailer Name</th>
                                    <th>Retailer State</th>
                                    <th>Retailer Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($created_retailers??[] as $retailer)
                                <tr >
                                    <td>{{ $retailer->created_at }}</td>
                                    <td>{{ $retailer->business_name }}</td>
                                    <td>{{ $retailer->state }}</td>
                                    <td>{{ $retailer->approval_status }}</td>

                                    <td>
                                        <a href="{{ route('admin.retailers.approve', $retailer->id) }}" title="Approve Retailer">
                                            <i class="fas fa-check-circle" style="font-size: 22px;
                                               color: green;"></i>
                                        </a>
                                        <a href="{{ route('admin.retailers.deny', $retailer->id) }}" class="" title="Deny Retailer">
                                            <i class="fas fa-minus-circle" style="font-size: 22px;
                                               color: red;"></i>
                                        </a>
                                        <a href="{{ route('retailer', $retailer->id, $retailer) }}" title="View Retailer">
                                            <i class="fas fa-eye" style="font-size: 22px;
                                               color: black;"></i>
                                        </a>
                                        <form action="{{ route('admin.retailers.delete', $retailer->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')" style="border-radius: 40%; margin-top: -5px; background-color: inherit" title="Delete Retailer">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                        @endif

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
<script src="{{ asset('js/retailer.js') }}"></script>

@endsection