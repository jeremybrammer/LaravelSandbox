<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremybrammer\laravelimagetos3package\laravelimagetos3package;
use jeremybrammer\laravelimagetos3package\Models\ImageUpload;
use jeremybrammer\laravelimagetos3package\Facades\LaravelImageToS3PackageFacade;

class ImageUploads extends Controller
{
    public function __construct(){}

    //Show the uploaded images and image upload form.
    public function index(){
        $data = [
            "allUploadedImages" => LaravelImageToS3PackageFacade::getAllUploadedImages()
        ];
        return view("imageuploads.index", $data);
    }

    //This handles the uploading and resizing when image upload form is submitted.
    public function store(Request $request){

        //A bit of validation to make sure there is a file.
        $validated = $request->validate([
            "image-upload-field" => "required"
        ],
        [
            "image-upload-field.required" => "You must choose an image file."
        ]);

        //Optionally override the image size settings in the upload service.
        LaravelImageToS3PackageFacade::setWidthByImageType("thumbnail", 100);
        LaravelImageToS3PackageFacade::setWidthByImageType("small", 200);
        //Call the upload handler with the request, html image field name attribute, and folder in s3 to store them.
        LaravelImageToS3PackageFacade::handUploadRequest($request, "image-upload-field", "victorycto/images");
        return redirect(route('imageuploads.index'))->with('success_message', 'File uploaded.'); //Return back to index with a message.
    }

    //This is a view to see individual images.
    public function view(ImageUpload $imageUpload, $imagetype){
        //Use route-model binding for the image object, and an image type to get the proper size.
        switch($imagetype){
            case "thumbnail": $url = $imageUpload->thumbnail_image_url; break;
            case "small": $url = $imageUpload->small_image_url; break;
            case "original": $url = $imageUpload->original_image_url; break;
            default: return; break;
        }
        // $imageURL = $this->imagetos3->preSignS3Url($imageUpload->original_image_url); //Sign s3 URL.
        $imageURL = LaravelImageToS3PackageFacade::preSignCloudFrontUrl($url); //Sign CloudFront URL.
        return view("imageuploads.view", ["imageURL" => $imageURL]);
    }

}
