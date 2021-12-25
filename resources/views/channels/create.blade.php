@extends('layouts.app')

@section('content')

<div class="container card">

    <div class="card-header">
        <h3>Create a new channel</h3>
    </div>

    <form method="post" action="/channels/store" enctype="multipart/form-data" class="card-body">
        @csrf

        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" required>

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-right">Description</label>

            <div class="col-md-6">
                <textarea class="form-control" name="description"></textarea>

                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="image" class="col-md-4 col-form-label text-md-right">Image</label>

            <div class="col-md-6">
                <input type="file" name="image" class="form-control"/>
{{--                <input id="image" type="text" class="form-control @error('image') is-invalid @enderror" name="image" required value="notfound.png">--}}

                @error('image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <span class="col-md-4"></span>

            <div class="col-md-6">
                <input type="submit" value="Create channel" class="btn btn-primary"/>
            </div>
        </div>


    </form>
</div>
@endsection
