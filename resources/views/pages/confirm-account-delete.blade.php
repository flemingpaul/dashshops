@extends('layout')

@section('content')
<main>
    <div class="container-fluid" style="background-color: #FEFBF2">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-7">


                    <h3 class="mt-5 text-center">Delete Your Account With The DashShop</h3>
                    <section>
                        <p class="mt-4 text-black-50 font-weight-bold text-center">This account is permanent and cannot be reversed. Are you sure you want to continue?</p>
                    </section>
                </div>
                <div class="card-footer col-md-7">
                    <form action="{{ route('delete-account') }}" method="POST">
                        @csrf
                        <a href="{{ route('analytics') }}" class="btn btn-info">Cancel</a>
                        <button type="submit" href="{{ route('delete-account') }}" class="btn text-danger">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection