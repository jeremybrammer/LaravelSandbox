<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use jeremybrammer\laravelimagetos3package\laravelimagetos3package;
// use jeremybrammer\laravelimagetos3package\ImageTos3Interface;
use jeremybrammer\laravelimagetos3package\Models\ImageUpload;
use jeremybrammer\laravelimagetos3package\Facades\LaravelImageToS3PackageFacade;

class ImageUploads extends Controller
{
    // private $imagetos3;

    public function __construct(){
        //dd($imagetos3);
        // $this->imagetos3 = $imagetos3;
    }
    public function index(){
        $data = [
            "someData" => "This is from the ImageUploads controller.",
            "testData" => LaravelImageToS3PackageFacade::test(),
            "allUploadedImages" => LaravelImageToS3PackageFacade::getAllUploadedImages()
        ];
        return view("imageuploads.index", $data);
    }

    public function store(Request $request){

        $validated = $request->validate([
            "image-upload-field" => "required"
        ],
        [
            "image-upload-field.required" => "You must choose an image file."
        ]);

        LaravelImageToS3PackageFacade::handUploadRequest($request);

        // $file = $request->file("image-upload-field");
        // dd($file->getSize());
        return redirect(route('imageuploads.index'))->with('success_message', 'File uploaded.');
    }

    public function view(ImageUpload $imageUpload){
        // $imageURL = $this->imagetos3->preSignS3Url($imageUpload->original_image_url); //Sign s3.
        $imageURL = LaravelImageToS3PackageFacade::preSignCloudFrontUrl($imageUpload->original_image_url); //Sign CloudFront.
        return view("imageuploads.view", ["imageURL" => $imageURL]);
    }

}
