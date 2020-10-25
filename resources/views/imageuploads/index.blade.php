@extends('layouts.app')

@section('content')
    <div class="card" style="width: 50rem;">
        <div class="card-body">
            <h5 class="card-title">Upload an Image</h5>
            <p class="card-text">Upload an image to s3.</p>
            <form id="image-upload" class="image-upload-form" action="{{ route("imageuploads.store") }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" id="image1" name="image-upload-field" accept="image/*"/>
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

            @if(session("success_message"))
                @component("components.success_message")
                    @slot("success_message")
                        {{ session("success_message") }}
                    @endslot
                @endcomponent
            @endif

        </div>
    </div>

    @foreach($allUploadedImages as $image)
        <div class="card" style="width: 50rem;">
            <div class="card-body">
                <h5 class="card-title">{{ $image->original_filename }}</h5>
                <p><img src="{{ $image->thumbnail_image_url_presigned }}" /></p>
                <ul>
                    <li><a href="{{ route("imageuploads.view", [$image->id, "thumbnail"]) }}">Thumbnail</a></li>
                    <li><a href="{{ route("imageuploads.view", [$image->id, "small"]) }}">Small</a></li>
                    <li><a href="{{ route("imageuploads.view", [$image->id, "original"]) }}">Original</a></li>
                </ul>
            </div>
        </div>
    @endforeach

@endsection
