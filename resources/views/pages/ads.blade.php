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
                        <h4 class="text-center" style="color: #7AB91D">{{$total_clicks}}</h4>
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">

                    <div class="card-body">

                        @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                        @endif

                        @error('image')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                        @if ($errors->has('total_clicks'))
                        <div class="alert alert-danger mt-1 mb-1">{{ $errors->first('total_clicks') }}</div>
                        @enderror
                        @if ($errors->has('url'))
                        <div class="alert alert-danger mt-1 mb-1">{{ $errors->first('url') }}</div>
                        @endif
                        @if ($errors->has('start_date'))
                        <div class="alert alert-danger mt-1 mb-1">{{ $errors->first('start_date') }}</div>
                        @endif
                        @if ($errors->has('end_date'))
                        <div class="alert alert-danger mt-1 mb-1">{{ $errors->first('end_date') }}</div>
                        @endif


                        <div class="d-flex flex-row justify-content-between">
                            <h5 class="ml-5 text-center mt-2">Ads</h5>
                            <button type="button" class="btn btn-success mr-5 mb-3" data-toggle="modal" data-target="#exampleModal">
                                Add Ads
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create Ad</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data" id="upload-image" action="{{ route('update-ads') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="edit_id"> 
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <img id="preview-image-before-upload" class="card-img-top" style="height: 9vw; width:100%; object-fit: cover;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQV6AHepDSiDuwZfDf-7AURR7uoxrOA7ASy8BnL1V3EzzbkWbpMlunU2CnGMTkSHuTq-A&usqp=CAU" alt="preview image">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="total_clicks" class="col-md-12 col-form-label">Ad Banner Images</label>
                                                            <input type="file" name="image" class="form-control" placeholder="Choose image" id="image">
                                                            @error('image')
                                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="total_clicks" class="col-md-12 col-form-label">Total Clicks</label>
                                                            <input type="number" class="form-control" name="total_clicks" id="total_clicks" required />
                                                            @if ($errors->has('total_clicks'))
                                                            <div class="alert alert-danger mt-1 mb-1">{{ $errors->first('total_clicks') }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>



                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-group">
                                                            <label for="url" class="col-md-12 col-form-label">Url</label>
                                                            <input type="url" id="url" class="form-control" name="url" required>
                                                            @if ($errors->has('url'))
                                                            <span class="text-danger">{{ $errors->first('url') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="url" class="col-md-12 col-form-label">Start Date</label>
                                                            <input type="date" class="form-control" name="start_date" id="start_date" required />
                                                            @if ($errors->has('start_date'))
                                                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="url" class="col-md-12 col-form-label">End Date</label>
                                                            <input type="date" class="form-control" name="end_date" id="end_date" required />
                                                            @if ($errors->has('end_date'))
                                                            <span class="text-danger">{{ $errors->first('end_date') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" id="submit">Submit</button>

                                            </div>
                                        </form>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <table id="table_ads" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr style="margin-bottom: 10%">
                                    <th>Image</th>
                                    <th>Url</th>
                                    <th class="mb-5">Date Created</th>
                                    <th class="mb-5">Period</th>
                                    <th class="mb-5">Subscribed Clicks</th>

                                    <th>Actions</th>
                                </tr>
                            </thead>
                            @foreach ($ads??[] as $ad)
                            <tr style="border-color: #FFFFFF; margin-top: 5%;  box-shadow: 5px 4px #efefef;">
                                <td><img src="{{asset('images/'.$ad->image) }}" alt="banner" style="height: 60px; width:250px; object-fit: cover;" /> </td>
                                <td>{{ $ad->url }}</td>
                                <td>{{ $ad->created_at }}</td>
                                <td><strong>[{{ date_format(date_create($ad->start_date),"D, j-M-Y H:i") }}]</strong> - <strong>[{{ date_format(date_create($ad->end_date),"D, j-M-Y H:i")  }}]</strong></td>
                                <td>{{$ad->subscribed_clicks}}/{{ $ad->total_clicks }}</td>

                                <td>
                                    
                                        <button type="button" class="btn btn-primary"  onclick="edit({{$ad->id}})">
                                            <i class="fas fa-edit" style="font-size: 22px;
                                               color: lightgreen;"></i>
                                        </button>
                                    

                                    <form action="{{ route('delete-ad', $ad->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')" class="btn btn-success" style="background-color: inherit; border: none">
                                            <i class="fas fa-trash" style="font-size: 22px;
                                               color: red;"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach

                        </table>
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
<script src="{{asset('js/ads.js')}}"></script>

@endsection