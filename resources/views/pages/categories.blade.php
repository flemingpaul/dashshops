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
                        <h4 class="text-center">130</h4>
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
                        <h4 class="text-center">130</h4>
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
                        <h4 class="text-center">130</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">

                        @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                        @endif

                        <table class="table">
                            <h5 class="text-center">Categories</h5>
                            <tr style="margin-bottom: 10%">
                                <th>Name</th>
                                <th>Banner Image</th>
                                <th class="mb-5">Date Created</th>
                                <th>Actions</th>
                                <th></th>
                            </tr>
                            @forelse ($categories as $category)
                            <tr style="border-color: #FFFFFF; margin-top: 5%;  box-shadow: 5px 4px #efefef;">
                                
                                <td><img width="50px" src="{{$category->badge!=null?asset('images/categories/'.$category->badge):''}}" alt=""> {{ $category->name }}</td>
                                <td><img class="" height="50px" src="{{$category->banner_image!=null?asset('images/categories/'.$category->banner_image):''}}" alt=""></td>
                                <td>{{ $category->created_at }}</td>

                                <td>
                                    <button data-toggle="modal" data-target="#editModal{{$category->id}}" style="border: none; background-color: inherit">
                                        <i class="fas fa-edit" style="font-size: 22px;
                                               color: lightgreen;"></i>
                                    </button>
                                    <div class="modal fade" id="editModal{{$category->id}}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('edit-category', $category->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body row">
                                                        <div class="col-md-4">
                                                            <div class="card" >
                                                                <img class="card-img-top" src="{{$category->banner_image!=null?asset('images/categories/'.$category->banner_image):''}}" alt="Banner Image">
                                                                <div class="card-body">
                                                                    <h5 class="card-title">Banner Image</h5>
                                                                    <p class="card-text"><img width="50px" src="{{$category->badge!=null?asset('images/categories/'.$category->badge):''}}" alt="Badge"></p>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">


                                                            <div class="form-group">
                                                                <label for="exampleInputPassword1">Category Name</label>
                                                                <input type="text" class="col-md-12 form-control" placeholder="{{$category->name}}" name="name" id="name">
                                                            </div>


                                                            <div class="form-group">
                                                                <label for="exampleInputPassword1">Banner Image</label>
                                                                <input type="file" class=" form-control" name="banner_image" />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputPassword1">Category Badge</label>
                                                                <input type="file" class=" form-control" name="badge" />
                                                            </div>

                                                            @error('banner_image')
                                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                            @enderror


                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('delete-category', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-success" style="background-color: inherit; border: none">
                                            <i class="fas fa-trash" style="font-size: 22px;
                                               color: red;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">No Categories found.</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection