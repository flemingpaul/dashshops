@extends('dashboard')


@section('content')

    <div class="row mb-3">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Banners</h2>
                <a class="btn btn-success" title="Upload files"> <i class="fas fa-upload fa-2x"></i>
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="container">
        <table class="table table-bordered table-responsive-lg thead-dark text-center">
            <thead class="thead-dark ">
            <tr>
                <th>No</th>
                <th>File Name</th>
                <th>Date Created</th>
                <th width="280px">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($files as $file)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $file->filename }}</td>
                    <td>{{ date_format($file->created_at, 'jS M Y') }}</td>
                    <td>
                        <form action="{{ route('files.destroy', $file->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" title="delete" style="border: none; background-color:transparent;">
                                <i class="fas fa-trash fa-lg text-danger"></i>

                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
