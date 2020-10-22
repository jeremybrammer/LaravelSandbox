@extends('layouts.app')

@section('content')
    <div class="card" style="width: 50rem;">
        <div class="card-body">
            <h5 class="card-title">Upload an Image</h5>
            <p class="card-text">Upload an image to s3. {{ $testData }}</p>

            {{-- <img src="{{ Storage::url('images/aD4cMvZoc7rlW39CbSALKCop0bmitcjuSO5M8YIk.png') }}" /> --}}

            <form id="image-upload" class="image-upload-form" action="{{ route("imageuploads.store") }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" id="image-upload-field" name="image-upload-field" accept="image/*"/>
                <button type="submit" class="btn btn-success">Upload</button>
            </form>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    </div>
@endsection
